<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $timestamps = false;
    protected $table = 'comments';
    protected $primaryKey = 'id_comment';
    
    protected $fillable = [
        'id_commenter',
        'id',
        'date_comment',
        'msg_ofcomment',
        'comment_likes',
    ];

    public function product()
    {
        return $this->belongsTo('App\Product', 'id');
    }

     public function commenter()
    {
        return $this->belongsTo('App\User', 'id_commenter');
    }
}

