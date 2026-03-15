<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductMetalOption extends Model
{
    protected $fillable = [
        'product_id',
        'metal_id',
        'metal_option_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function metal(): BelongsTo
    {
        return $this->belongsTo(Metal::class);
    }

    public function metalOption(): BelongsTo
    {
        return $this->belongsTo(MetalOption::class);
    }
}
