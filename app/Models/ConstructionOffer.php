<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConstructionOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'construction_request_id',
        'constructor_id',
        'price',
        'currency',
        'construction_time_days',
        'offer_details',
        'status',
        'accepted_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
    ];

    /**
     * Get the construction request associated with this offer.
     */
    public function constructionRequest(): BelongsTo
    {
        return $this->belongsTo(ConstructionRequest::class);
    }

    /**
     * Get the constructor who made this offer.
     */
    public function constructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'constructor_id');
    }
}
