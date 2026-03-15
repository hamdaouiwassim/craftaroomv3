<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Concept extends Model
{
    protected $fillable = [
        'name',
        'size',
        'category_id',
        'style_type',
        'description',
        'reel',
        'user_id',
        'status',
        'source',
        'is_resizable',
    ];

    protected $casts = [
        'is_resizable' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'concept_room', 'concept_id', 'room_id')->withTimestamps();
    }

    public function metals(): BelongsToMany
    {
        return $this->belongsToMany(Metal::class, 'concept_metal', 'concept_id', 'metal_id')->withTimestamps();
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Media::class, 'attachment_id', 'id')->where('type', 'concept');
    }

    public function threedmodels(): HasOne
    {
        return $this->hasOne(Media::class, 'attachment_id', 'id')->where('type', 'concept_threedmodel');
    }

    public function measure(): HasOne
    {
        return $this->hasOne(ConceptMeasure::class, 'concept_id', 'id');
    }

    public function reelLikes(): MorphMany
    {
        return $this->morphMany(ReelLike::class, 'reelable');
    }

    public function reelComments(): MorphMany
    {
        return $this->morphMany(ReelComment::class, 'reelable');
    }

    public function reelShares(): MorphMany
    {
        return $this->morphMany(ReelShare::class, 'reelable');
    }

    /**
     * Designer's chosen sub-metal (metal option) per metal for customization.
     */
    public function conceptMetalOptions(): HasMany
    {
        return $this->hasMany(ConceptMetalOption::class);
    }

    /**
     * Custom submaterials created specifically for this concept.
     */
    public function conceptCustomMetalOptions(): HasMany
    {
        return $this->hasMany(ConceptCustomMetalOption::class);
    }
}
