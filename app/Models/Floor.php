<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Floor extends Model
{
    protected $fillable = [
        'name',
        'icon',
    ];

    /**
     * Get all floor models for this floor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function floorModels(): HasMany
    {
        return $this->hasMany(FloorModel::class, 'floor_id', 'id');
    }
}
