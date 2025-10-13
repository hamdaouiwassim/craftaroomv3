<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Country extends Model
{
    //

    /**
     * Get the currency associated with the Country
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currency(): HasOne
    {
        return $this->hasOne(Currency::class);
    }
}
