<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckUserState
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if($user != null) {
            if ($user->state_user === "banned") {
                $userReport = DB::table('users')
                ->join('reports', 'users.id', '=', 'reports.id_punished')
                ->where('consequence','ban')
                ->orderBy('date_begin_punishement','DESC')
                ->first();
                
                $final_Date = (new Carbon($userReport->date_begin_punishement))->addDays(intval($userReport->punishement_span));
                $message = "This user has been banned until " . $final_Date;// Carbon::parse($final_Date)->format('d/m/Y');
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', $message);
            } else if($user->state_user === "suspended") {
                $message = "This user has been suspended temporarily";
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', $message);
            } else if($user->state_user === "inactive") {
                $message = "This account has been deleted permantly";
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', $message);
            }
        }

        return $next($request);
    }
}
