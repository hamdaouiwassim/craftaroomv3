<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ConceptMeasure extends Model
{
    protected $fillable = ['size', 'concept_id'];

    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class);
    }

    public function dimension(): HasOne
    {
        return $this->hasOne(ConceptDimension::class, 'concept_measure_id', 'id');
    }

    public function weight(): HasOne
    {
        return $this->hasOne(ConceptWeight::class, 'concept_measure_id', 'id');
    }
}
