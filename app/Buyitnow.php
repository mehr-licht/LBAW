<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buyitnow extends Model
{
    public $timestamps  = false;
    protected $table = 'buyitnows';
    protected $primaryKey = 'id_buy';

    public function products()
    {
        return $this->belongsTo('App\Product', 'id');
    }

    public function setDateEndAttribute($value)
    {
        $this->attributes['date_end'] = $value;
    }
}