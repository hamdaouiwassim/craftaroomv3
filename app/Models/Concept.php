<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Concept extends Model
{
    protected $fillable = [
        'name',
        'size',
        'category_id',
        'description',
        'reel',
        'user_id',
        'status',
        'source',
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

    /**
     * Designer's chosen sub-metal (metal option) per metal for customization.
     */
    public function conceptMetalOptions(): HasMany
    {
        return $this->hasMany(ConceptMetalOption::class);
    }
}
