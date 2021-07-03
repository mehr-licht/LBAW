<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Postal extends Model
{
    //protected $table = 'biddings';
    protected $primaryKey = 'id_postal';

    protected $fillable = [
        'id_postal',
        'postal_code',
        'id_city',
    ];

    public function citys()
    {
        return $this->belongsTo('App\City', 'id_city');
    }

    public function users()
    {
        return $this->hasMany('App\User', 'id','id_postal');
    }

}

