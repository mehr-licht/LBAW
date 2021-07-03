<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{   
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Easter egg to check the last build in prodution
     */
    public function build(){
        $filename = '../';
        dd(date('F d Y h:i A', filemtime($filename)+3600 ) );
    }

    /**
     * Easter egg to check log information in production
     */
    public function log(){
        
        dd(readfile('../storage/logs/ebaw.log'));
    }
}
