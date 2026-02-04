<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Room extends Model
{
    use HasFactory;
    protected $fillable = [
            'name',
            'photoUrl_id'
    ];



    /**
     * The products that belong to the room.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * The concepts that belong to the room.
     */
    public function concepts(): BelongsToMany
    {
        return $this->belongsToMany(Concept::class);
    }

}
