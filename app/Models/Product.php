<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
        'size',
        'category_id',
        'description',
        'currency',
        'reel',
        'user_id',
        'status'
];

/**
 * Get the user that owns the Product
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
 */
public function user(): BelongsTo
{
    return $this->belongsTo(User::class, 'user_id', 'id');
}

    /**
     * Get all of the threedmodels for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function threedmodels(): HasMany
    // {
    //     return $this->hasMany(Threedmodel::class, 'product_id', 'id');
    // }
    public function threedmodels(): HasOne
        {
            return $this->hasOne(Media::class, 'attachment_id', 'id')->where('type','threedmodel');
        }

    /**
     * Get the photos associated with the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos(): HasMany
    {
        return $this->hasMany(Media::class, 'attachment_id', 'id')->where('type', '=', 'product');;
    }


    /**
     * The rooms that belong to the product.
     */
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class,'product_room', 'product_id', 'room_id')->withTimestamps();
    }

      /**
     * The rooms that belong to the product.
     */
    public function metals(): BelongsToMany
    {
        return $this->belongsToMany(Metal::class,'metal_product', 'product_id', 'metal_id')->withTimestamps();
    }

    /**
     * Get the category that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }



    /**
     * Get the measure associated with the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function measure(): HasOne
    {
        return $this->hasOne(Measure::class, 'product_id', 'id');
    }

    /**
     * Get all of the reviews for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the currency associated with the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currency(): HasOne
    {
        return $this->hasOne(Currency::class);
    }
}
