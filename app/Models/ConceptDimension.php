<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConceptDimension extends Model
{
    protected $fillable = ['length', 'width', 'height', 'unit', 'concept_measure_id'];

    public function conceptMeasure(): BelongsTo
    {
        return $this->belongsTo(ConceptMeasure::class, 'concept_measure_id', 'id');
    }
}
