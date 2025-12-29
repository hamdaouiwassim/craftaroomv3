<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Toggle favorite status for a product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggle(Product $product)
    {
        $favorite = Favorite::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($favorite) {
            // Remove from favorites
            $favorite->delete();
            return response()->json([
                'success' => true,
                'message' => 'Product removed from favorites.',
                'is_favorite' => false
            ]);
        } else {
            // Add to favorites
            Favorite::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Product added to favorites.',
                'is_favorite' => true
            ]);
        }
    }

    /**
     * Check if product is favorited by current user.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function check(Product $product)
    {
        $isFavorite = Favorite::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->exists();

        return response()->json([
            'is_favorite' => $isFavorite
        ]);
    }
}

