<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report_product extends Model
{
    protected $primaryKey = 'id_report';

    public $timestamps = false;

    public function report()
    {
        return $this->hasOne('App\Report', 'id', 'id_report');
    }
    
    public function products()
    {
        return $this->hasOne('App\Product', 'id', 'id_product');
    }
}