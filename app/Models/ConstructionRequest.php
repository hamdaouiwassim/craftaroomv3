<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class ConstructionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_type',
        'concept_id',
        'product_id',
        'customer_id',
        'target_producer_id',
        'message',
        'customer_notes',
        'viewer_state_json',
        'requested_dimensions_json',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'requested_dimensions_json' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function getNormalizedRequestedDimensionsAttribute(): ?array
    {
        $dimensions = $this->requested_dimensions_json;

        if (!is_array($dimensions)) {
            return null;
        }

        $normalizeNumber = static function ($value) {
            if ($value === null || $value === '') {
                return null;
            }

            if (!is_numeric($value)) {
                return null;
            }

            $numeric = (float) $value;

            return fmod($numeric, 1.0) === 0.0
                ? (string) (int) $numeric
                : rtrim(rtrim(number_format($numeric, 2, '.', ''), '0'), '.');
        };

        $normalized = [
            'size' => filled($dimensions['size'] ?? null) ? strtoupper(trim((string) $dimensions['size'])) : null,
            'length' => $normalizeNumber($dimensions['length'] ?? null),
            'width' => $normalizeNumber($dimensions['width'] ?? null),
            'height' => $normalizeNumber($dimensions['height'] ?? null),
            'unit' => filled($dimensions['unit'] ?? null) ? strtoupper(trim((string) $dimensions['unit'])) : null,
        ];

        if ($normalized['size'] === null &&
            $normalized['length'] === null &&
            $normalized['width'] === null &&
            $normalized['height'] === null) {
            return null;
        }

        return $normalized;
    }

    public function getCustomizationSummaryAttribute(): array
    {
        $viewerState = $this->viewer_state_json;

        if (blank($viewerState)) {
            return [];
        }

        $decoded = $viewerState;

        try {
            for ($attempt = 0; $attempt < 3; $attempt++) {
                if (!is_string($decoded)) {
                    break;
                }

                $decoded = json_decode($decoded, true, 512, JSON_THROW_ON_ERROR);
            }
        } catch (\Throwable $e) {
            return [];
        }

        if (!is_array($decoded) || !is_array($decoded['materials'] ?? null)) {
            return [];
        }

        return collect($decoded['materials'])
            ->filter(fn ($material) => is_array($material) && filled($material['name'] ?? null))
            ->map(function (array $material) {
                $texture = $material['texture'] ?? null;
                $textureUrl = $this->resolveTextureUrl($texture);
                $color = filled($material['color'] ?? null) ? strtoupper((string) $material['color']) : null;

                return [
                    'name' => trim((string) ($material['name'] ?? '')),
                    'color' => $color,
                    'color_name' => $this->resolveColorName($color),
                    'texture' => filled($texture) ? Str::of(basename((string) $texture))->beforeLast('.')->replace(['_', '-'], ' ')->title()->value() : null,
                    'texture_url' => $textureUrl,
                ];
            })
            ->values()
            ->all();
    }

    private function resolveTextureUrl(?string $texture): ?string
    {
        if (!filled($texture)) {
            return null;
        }

        $texture = trim($texture);

        if (Str::startsWith($texture, ['http://', 'https://'])) {
            return $texture;
        }

        if (Str::startsWith($texture, '/')) {
            return URL::to($texture);
        }

        return URL::to('/storage/' . ltrim($texture, '/'));
    }

    private function resolveColorName(?string $hex): ?string
    {
        if (!filled($hex)) {
            return null;
        }

        return [
            '#FFFFFF' => 'White',
            '#000000' => 'Black',
            '#FF0000' => 'Red',
            '#00FF00' => 'Green',
            '#0000FF' => 'Blue',
            '#FFFF00' => 'Yellow',
            '#FFA500' => 'Orange',
            '#800080' => 'Purple',
            '#FFC0CB' => 'Pink',
            '#A52A2A' => 'Brown',
            '#808080' => 'Gray',
            '#C0C0C0' => 'Silver',
            '#FFD700' => 'Gold',
            '#FF00A3' => 'Pink',
        ][$hex] ?? null;
    }

    /**
     * Get the concept associated with this request.
     */
    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class);
    }

    /**
     * Get the product associated with this request (for product flows).
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the customer who made this request.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the targeted producer for direct product requests.
     */
    public function targetProducer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_producer_id');
    }

    /**
     * Get the offers for this construction request.
     */
    public function offers(): HasMany
    {
        return $this->hasMany(ConstructionOffer::class);
    }

    /**
     * Get the accepted offer for this request.
     */
    public function acceptedOffer()
    {
        return $this->offers()->where('status', 'accepted')->first();
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function canBeEditedAsDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function canBeEditedByCustomer(): bool
    {
        return in_array($this->status, ['pending', 'declined'], true);
    }

    public function canBeCanceledByCustomer(): bool
    {
        return !in_array($this->status, ['draft', 'accepted', 'completed', 'canceled'], true);
    }
}
