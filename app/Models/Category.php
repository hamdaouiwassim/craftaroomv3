<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'type',
        'status'
];
/**
 * Get all of the products for the Category
 *
 * @return \Illuminate\Database\Eloquent\Relations\HasMany
 */
public function products(): HasMany
{
    return $this->hasMany(Product::class);
}

/**
 * Get all of the concepts for the Category
 *
 * @return \Illuminate\Database\Eloquent\Relations\HasMany
 */
public function concepts(): HasMany
{
    return $this->hasMany(Concept::class);
}

/**
 * Get all of the sub_categories for the Category
 *
 * @return \Illuminate\Database\Eloquent\Relations\HasMany
 */
public function sub_categories(): HasMany
{
    return $this->hasMany(Category::class,"category_id","id");
}

/**
 * Get the category that owns the Category
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
 */
public function main_category(): BelongsTo
{
    return $this->belongsTo(category::class);
}

/**
 * Get the icon associated with the Category
 *
 * @return \Illuminate\Database\Eloquent\Relations\HasOne
 */
public function icon(): HasOne
{
    return $this->hasOne(Media::class, 'attachment_id', 'id');
}
}
