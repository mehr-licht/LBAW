<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bidding extends Model
{
    public $timestamps = false;
    protected $table = 'biddings';
    protected $primaryKey = 'id_bid';

    protected $fillable = [
        'id_auction',
        'bidder',
        'value_bid',
        'bidding_date',
    ];

    public function auction()
    {
        return $this->belongsTo('App\Auction', 'id_auction');
    }

    public function bidder()
    {
        return $this->belongsTo('App\User', 'id');
    }
}

