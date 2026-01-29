<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetalOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'metal_id',
        'ref',
        'name',
        'image_url',
    ];

    public function metal()
    {
        return $this->belongsTo(Metal::class);
    }
}
