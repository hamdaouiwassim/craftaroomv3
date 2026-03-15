<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConceptCustomMetalOption extends Model
{
    protected $fillable = [
        'concept_id',
        'metal_id',
        'name',
        'ref',
        'image_url',
    ];

    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class);
    }

    public function metal(): BelongsTo
    {
        return $this->belongsTo(Metal::class);
    }
}
