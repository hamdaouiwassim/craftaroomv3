<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConceptMetalOption extends Model
{
    protected $table = 'concept_metal_option';

    protected $fillable = [
        'concept_id',
        'metal_id',
        'metal_option_id',
    ];

    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class);
    }

    public function metal(): BelongsTo
    {
        return $this->belongsTo(Metal::class);
    }

    public function metalOption(): BelongsTo
    {
        return $this->belongsTo(MetalOption::class);
    }
}
