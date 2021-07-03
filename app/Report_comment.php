<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report_comment extends Model
{
    protected $primaryKey = 'id_report';

    public $timestamps = false;

    public function report()
    {
        return $this->hasOne('App\Report', 'id', 'id_report');
    }

    public function comment()
    {
        return $this->hasOne('App\Comment', 'id', 'id_comment');
    }
}