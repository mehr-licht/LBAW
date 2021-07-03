<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report_user extends Model
{
    protected $primaryKey = 'id_report';

    public $timestamps = false;

    public function report()
    {
        return $this->hasOne('App\Report', 'id', 'id_report');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'id_user');
    }
}