<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Metal extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref',
        'name',
        'image_url',
        'photoUrl_id',
    ];

    /**
     * Sub options for this metal.
     */
    public function metalOptions()
    {
        return $this->hasMany(MetalOption::class);
    }

    /**
     * Products linked to this metal.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
