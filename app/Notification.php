<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = false;
    protected $table = 'notifications';
    protected $primaryKey = 'id_notif';
    
    protected $fillable = [
        'id_notif', 'id_user', 'is_new', 'text_notification', 'type_ofnotification', 'id_item', 'id_comment'
    ];

    /**
     * Returns all new notifications
     */
    public function scopeNew($query)
    {
        return $query->where('is_new', 'true');
    }


}
