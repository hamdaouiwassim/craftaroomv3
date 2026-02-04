<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConstructionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'concept_id',
        'customer_id',
        'message',
        'customer_notes',
        'status',
    ];

    /**
     * Get the concept associated with this request.
     */
    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class);
    }

    /**
     * Get the customer who made this request.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the offers for this construction request.
     */
    public function offers(): HasMany
    {
        return $this->hasMany(ConstructionOffer::class);
    }

    /**
     * Get the accepted offer for this request.
     */
    public function acceptedOffer()
    {
        return $this->offers()->where('status', 'accepted')->first();
    }
}
