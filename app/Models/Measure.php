<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Measure extends Model
{
    use HasFactory;


    /**
     * Get the dimension associated with the Measure
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function dimension(): HasOne
    {
        return $this->hasOne(Dimension::class, 'measure_id', 'id');
    }

    /**
     * Get the weight associated with the Measure
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function weight(): HasOne
    {
        return $this->hasOne(Weight::class,  'measure_id', 'id');
    }

}
