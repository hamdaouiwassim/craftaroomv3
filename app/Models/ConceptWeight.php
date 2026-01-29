<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConceptWeight extends Model
{
    protected $fillable = ['weight_value', 'weight_unit', 'concept_measure_id'];

    public function conceptMeasure(): BelongsTo
    {
        return $this->belongsTo(ConceptMeasure::class, 'concept_measure_id', 'id');
    }
}
