<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'id',
        'id_admin',
        'id_punished',
        'consequence',
        'state_report',
        'observation_admin',
        'reason',
        'text_report',
        'date_begin_punishement',
        'punishement_span',
        'id_reporter'
    ];

    /**
     * Scope a query to include only active reports
     */
    public function scopeAssumeOrAssumed($query)
    {
        return $query->where('state_report', '=', 'assumed')->orWhere('state_report', '=', 'assume');
    }

    /**
     * Scope a query to include only active reports
     */
    public function scopeAssumedOrDone($query)
    {
        return $query->where('state_report', '=', 'assumed')->orWhere('state_report', '=', 'done');
    }

    /**
     * Scope a query to include only reports that doesn't have admin
     */
    public function scopeAssume($query)
    {
        return $query->where('state_report', '=', 'assume');
    }
    /**
     * Scope a query to include only reports assumed by an admin
     */
    public function scopeAssumed($query)
    {
        return $query->where('state_report', '=', 'assumed');
    }

    /**
     * Scope a query to include only reports done by an admin
     */
    public function scopeDone($query)
    {
        return $query->where('state_report', '=', 'done');
    }

    /**
     * The user who does the report
     * iddo user matches id_reporter of Report
     */
    public function userReporter()
    {
        return $this->belongsTo('App\User', 'id_reporter');
    }

    /**
     * 
     */
    public function report_users()
    {
        return $this->belongsTo('App\Report_user', 'id');
    }

    /**
     * 
     */
    public function report_comments()
    {
        return $this->belongsTo('App\Report_comment', 'id');
    }
    /*
     *
     */
    public function report_products()
    {
        return $this->belongsTo('App\Report_product', 'id');
    }

    /**
     * The admin who owns the report
     */
    public function admin()
    {
        return $this->belongsTo('App\Admin', 'id_admin');
    }
}
