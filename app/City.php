<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $primaryKey = 'id_city';

    protected $fillable = [
        'id_city',
        'city_name',
    ];

    public function postals()
    {
        return $this->hasMany('App\Postal', 'id_postal','id_city');
    }
}

