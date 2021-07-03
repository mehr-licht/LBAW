<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;

class NotificationController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Get notifications for the user in the Navbar
     * @param  \Illuminate\Http\Request  $request
     *  */
    public function getNotifications(Request $request)
    {
        try{
            $notifications = Notification::where('id_user', '=' , $request->id)->get();
            return response()->json(array( 'notifications' => $notifications ) , 200);
        }catch(ModelNotFoundException $e){
            Log::channel('storeLog')->error('On model ' . $e->getModel() ) ;
        }    
    }
}
