<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Threedmodel extends Model
{
    use HasFactory;

    /**
     * Get the product that owns the Threedmodel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Get all of the MtlPhotosUrls for the Threedmodel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function MtlPhotosUrls(): HasMany
    {
        return $this->hasMany(Media::class, 'attachment_id', 'id');
    }
}
