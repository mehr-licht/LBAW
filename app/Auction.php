<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    public $timestamps  = true;
    protected $primaryKey = 'id_auction';

    public function product()
    {
        return $this->belongsTo('App\Product', 'id');
    }

    public function bids()
    {
        return $this->hasMany('App\Bidding', 'id_bid','id_auction');
    }
}