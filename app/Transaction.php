<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public $timestamps = false;
    protected $table = 'transactions';
    protected $primaryKey = 'id_transac';

    protected $fillable = [
        'id_transac',
        'id_buyer',
        'id_seller',
        'id',
        'vote_inSeller',
        'vote_inBuyer',
        'date_payment',
        'value'
    ];

    public function buyitnow()
    {
        return $this->hasOneThrough('App\Product', 'App\Buyitnow', 'id', 'id_buy');
    }

      public function auction()
    {
        return $this->hasOneThrough('App\Product', 'App\Auction', 'id', 'id_auction');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function buyer()
    {    //se o product->is owner !== user
        return $this->belongsTo('App\User');
    }

    public function seller()
    {   //se o product->is owner === user
        return $this->belongsTo('App\User');
    }

}
?>