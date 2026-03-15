<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductCustomMetalOption extends Model
{
    protected $fillable = [
        'product_id',
        'metal_id',
        'name',
        'ref',
        'image_url',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function metal(): BelongsTo
    {
        return $this->belongsTo(Metal::class);
    }
}
