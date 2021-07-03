<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use ImageTrait;
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'name',
        'password',
        'photo',
        'description',
        'date_register',
        'state_user',
        'phone_number',
        'address',
        'id_postal',
        'birth_date',
        'total_votes'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The products this user owns.
     */
    public function products()
    {
        return $this->hasMany('App\Product');
    }

    /**
     * The comments this user owns
     */
    public function comments()
    {
        return $this->hasMany('App\Comment', 'id_comment', 'id');
    }

    /**
     * The bids this user did
     */
    public function bids()
    {
        return $this->hasMany('App\Bidding', 'id_bid', 'id');
    }

    /**
     * Full text Search
     */
    public function scopeFTS($query, $search)
    {
        if(!empty($search))
            $search = $search . ':*';
        return $query->selectRaw('id, username, email')
            //plainto_tsquery simplifies  the to_tsquery 
            ->whereRaw("to_tsvector('english', username) @@ to_tsquery('english', ?)", [$search])
            ->orderByRaw("ts_rank(to_tsvector('english', username), to_tsquery('english', ?)) DESC", [$search]);
        //Considering that search is a pre-calculated column containing the ts_vector of the columns we want to search.
    }

    public function postals()
    {
        return $this->belongsTo('App\Postal', 'id_postal');
    }
}
