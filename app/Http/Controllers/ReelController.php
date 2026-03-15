<?php

namespace App\Http\Controllers;

use App\Models\Concept;
use App\Models\Product;
use App\Models\ReelShare;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ReelController extends Controller
{
    public function index()
    {
        $products = Product::query()
            ->where('status', 'active')
            ->whereNotNull('reel')
            ->where('reel', '!=', '')
            ->with([
                'photos',
                'category',
                'user.avatar',
                'reelComments.user.avatar',
            ])
            ->withCount(['reelLikes', 'reelComments', 'reelShares'])
            ->get();

        $concepts = Concept::query()
            ->where('status', 'active')
            ->whereNotNull('reel')
            ->where('reel', '!=', '')
            ->with([
                'photos',
                'category',
                'user.avatar',
                'reelComments.user.avatar',
            ])
            ->withCount(['reelLikes', 'reelComments', 'reelShares'])
            ->get();

        $reels = $this->normalizeReels($products, 'product')
            ->concat($this->normalizeReels($concepts, 'concept'))
            ->sortByDesc('created_at')
            ->values();

        return view('reels.index', compact('reels'));
    }

    public function toggleLike(Request $request, string $type, int $id): JsonResponse
    {
        $reelable = $this->resolveReelable($type, $id);

        $like = $reelable->reelLikes()->where('user_id', $request->user()->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $reelable->reelLikes()->create([
                'user_id' => $request->user()->id,
            ]);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $reelable->reelLikes()->count(),
        ]);
    }

    public function storeComment(Request $request, string $type, int $id): JsonResponse
    {
        $reelable = $this->resolveReelable($type, $id);

        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        $comment = $reelable->reelComments()->create([
            'user_id' => $request->user()->id,
            'comment' => $validated['comment'],
        ]);

        $comment->load('user.avatar');

        return response()->json([
            'success' => true,
            'comments_count' => $reelable->reelComments()->count(),
            'comment' => [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'created_at' => $comment->created_at->diffForHumans(),
                'user' => [
                    'name' => $comment->user?->name,
                    'avatar' => $comment->user?->photoUrl,
                    'avatar_media' => $comment->user?->avatar?->url,
                ],
            ],
        ]);
    }

    public function storeShare(Request $request, string $type, int $id): JsonResponse
    {
        $reelable = $this->resolveReelable($type, $id);

        ReelShare::create([
            'user_id' => $request->user()?->id,
            'reelable_type' => $reelable::class,
            'reelable_id' => $reelable->id,
        ]);

        return response()->json([
            'success' => true,
            'shares_count' => $reelable->reelShares()->count(),
        ]);
    }

    protected function normalizeReels(Collection $items, string $type): Collection
    {
        return $items->map(function ($item) use ($type) {
            $ownerAvatar = $item->user?->photoUrl ?: $item->user?->avatar?->url;
            $isLiked = auth()->check()
                ? $item->reelLikes()->where('user_id', auth()->id())->exists()
                : false;

            return [
                'key' => $type . '-' . $item->id,
                'type' => $type,
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'reel_url' => $item->reel,
                'poster_url' => $item->photos->first()?->url,
                'detail_url' => $type === 'product'
                    ? route('products.show', $item->id)
                    : route('concepts.show', $item->id),
                'owner_name' => $item->user?->name ?? ($type === 'concept' && $item->source === 'library' ? 'Library' : 'Craftaroom'),
                'owner_avatar' => $ownerAvatar,
                'category_name' => $item->category?->name,
                'source_label' => $type === 'product'
                    ? 'Product'
                    : ($item->source === 'library' ? 'Library Concept' : 'Designer Concept'),
                'likes_count' => $item->reel_likes_count,
                'comments_count' => $item->reel_comments_count,
                'shares_count' => $item->reel_shares_count,
                'is_liked' => $isLiked,
                'comments' => $item->reelComments
                    ->sortByDesc('created_at')
                    ->take(12)
                    ->values()
                    ->map(function ($comment) {
                        return [
                            'id' => $comment->id,
                            'comment' => $comment->comment,
                            'created_at' => $comment->created_at->diffForHumans(),
                            'user' => [
                                'name' => $comment->user?->name,
                                'avatar' => $comment->user?->photoUrl,
                                'avatar_media' => $comment->user?->avatar?->url,
                            ],
                        ];
                    }),
                'created_at' => $item->created_at,
            ];
        });
    }

    protected function resolveReelable(string $type, int $id): Product|Concept
    {
        return match ($type) {
            'product' => Product::query()
                ->where('status', 'active')
                ->whereNotNull('reel')
                ->where('reel', '!=', '')
                ->findOrFail($id),
            'concept' => Concept::query()
                ->where('status', 'active')
                ->whereNotNull('reel')
                ->where('reel', '!=', '')
                ->findOrFail($id),
            default => abort(404),
        };
    }
}
