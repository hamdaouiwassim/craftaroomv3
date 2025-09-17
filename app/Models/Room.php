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
     * The users that belong to the role.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

}
