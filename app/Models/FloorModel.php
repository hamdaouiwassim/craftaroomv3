<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FloorModel extends Model
{
    protected $fillable = [
        'floor_id',
        'name',
        'url',
        'path',
        'size',
        'image',
    ];

    /**
     * Get the floor that owns this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function floor(): BelongsTo
    {
        return $this->belongsTo(Floor::class, 'floor_id', 'id');
    }
}
