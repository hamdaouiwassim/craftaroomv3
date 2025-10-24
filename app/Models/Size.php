<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Size extends Model
{
    use HasFactory;

    protected $fillable = [
            'name',
            'value'
    ];

    /**
     * Get the measure that owns the Size
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function measure(): BelongsTo
    {
        return $this->belongsTo(Measure::class);
    }
}
