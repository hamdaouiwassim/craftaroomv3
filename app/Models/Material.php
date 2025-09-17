<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'colors'
    ];

    /**
     * Get the photo associated with the Material
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function photo(): HasOne
    {
        return $this->hasOne(Media::class, 'attachment_id', 'id');
    }
}
