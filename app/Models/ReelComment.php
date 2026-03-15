<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ReelComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reelable_type',
        'reelable_id',
        'comment',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reelable(): MorphTo
    {
        return $this->morphTo();
    }
}
