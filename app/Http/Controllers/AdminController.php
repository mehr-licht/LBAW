<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;
use App\User;
use App\Report;
use App\Report_user;
use App\Report_product;
use App\Report_comment;
use App\Admin;

class AdminController extends Controller
{

    public function AuthRouteAPI(Request $request)
    {
        return $request->admin();
    }

    /*
     * Admin dashboard display
     */
    public function home()
    {
        try {
            $admin_id = Auth::guard('admin')->Id();

            $admin = Auth::guard('admin')->getUser()->username;

            $report = Report::where('id_admin', '=', $admin_id)->assumedOrDone()->with(['userReporter'])->orderBy('state_report')->orderBy('date_report','desc')->paginate(10);

            return view('pages.admin', ['report' => $report]);
        } catch (ModelNotFoundException $e) {
            Log::channel('storeLog')->error('On model ' . $e->getModel());
        }
    }

    /*
     * Admin dashboard display with filters
     */
    public function homeFilter(Request $request)
    {
        try{
            $admin_id = Auth::guard('admin')->Id();

            $admin = Auth::guard('admin')->getUser()->username;

            if(!$request->has('filter') || $request->input('filter') == 'myDens')
                $report = Report::where('id_admin', '=', $admin_id)->assumedOrDone()->with(['userReporter'])->orderBy('state_report')->orderBy('date_report','desc')->paginate(10);
            else if($request->input('filter') == 'toBeSolved') {
                $report = Report::assume()->with(['userReporter'])->orderBy('date_report','desc')->paginate(10);
            } else if($request->input('filter') == 'solving') {
                $report = Report::assumed()->with(['userReporter'])->orderBy('date_report','desc')->paginate(10);
            } else if($request->input('filter') == 'solved') {
                $report = Report::done()->with(['userReporter'])->orderBy('date_report','desc')->paginate(10);
            } else if($request->input('filter') == 'byDays') {
                $report = Report::with(['userReporter'])->orderBy('date_report','desc')->paginate(10);
            } else {
                $report = Report::with(['userReporter'])->orderBy('id')->paginate(10);
            }

            return view('pages.admin', ['report' => $report, 'filter' => $request->input('filter')]);
        }catch(ModelNotFoundException $e){
            Log::channel('storeLog')->error('On model ' . $e->getModel() ) ;
        }
    }
    

    /**
     * Show report
     * @param int $id reports id to show 
     */
    public function show($id)
    {

        try {
            if (!Auth::guard('admin')->check()) {
                return redirect('/admin/login');
            }
            $report = DB::table('reports')->where('id', '=', $id)->get();
            $admin = null;
            if ($report[0]->state_report != 'assume')
                $admin = DB::table('admins')->where('id_admin', '=', $report[0]->id_admin)->first();

            $reporter = DB::table('users')->where('id', '=', $report[0]->id_reporter)->get();

            $punished = DB::table('users')->where('id', '=', $report[0]->id_punished)->get();

            $type_report;
            $id_conteudo = DB::table('report_comments')->where('id_report', $report[0]->id);
            if ($id_conteudo->exists()) {
                $type_report = "comment";
                $id_comment = $id_conteudo->value('id_comment');
                $id_conteudo = DB::table('comments')
                    ->where('id_comment', '=', $id_comment)->value('id');
            } else {
                $id_conteudo = DB::table('report_products')->where('id_report', $report[0]->id);
                if ($id_conteudo->exists()) {
                    $type_report = "product";
                    $id_conteudo = $id_conteudo->value('id_product');
                } else {
                    $type_report = "user";
                    $id_conteudo = $punished[0]->id;
                }
            }

            return view('pages.report', [
                'report' => $report,
                'admin' => $admin,
                'reporter' => $reporter,
                'punished' => $punished,
                'typeOfReport' => $type_report,
                'id_conteudo' => $id_conteudo
            ]);
        } catch (ModelNotFoundException $e) {
            Log::channel('storeLog')->error('On model ' . $e->getModel());
        }
    }

    /**
     * Update Report Status
     * @param  \Illuminate\Http\Request  $request
     */
    public function updateReportStatus(Request $request)
    {
        try {
            if (!Auth::guard('admin')->check()) {
                return redirect('/admin/login');
            }

            $id = $request->input('reportId');
            $report = Report::findOrFail($id);
            if ($report->id_admin === Auth::guard('admin')->Id() && $report->state_report === "assumed") {
                $report->state_report = 'assume';
                $report->id_admin = null;
                $report->save();
            } else {
                $report->state_report = 'assumed';
                $report->id_admin = Auth::guard('admin')->Id();
                $report->save();
            }

            return $this->show($id);
        } catch (ModelNotFoundException $e) {
            Log::channel('storeLog')->error('On model ' . $e->getModel());
        }
    }

    /**
     * Update the consequence status onthe user
     * @param  \Illuminate\Http\Request  $request
     * @param int $idUser id of user to be banned
     */
    public function ban($idUser, Request $request)
    {
        try {
            if (!Auth::guard('admin')->check()) {
                return redirect('/admin/login');
            }

            if (!$request->has('reportId')) {
                $validator = Validator::make($request->all(), [
                    'consequence' => 'required|in:ban',
                    'id_product' => 'nullable|integer|min:1|exists:products,id',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->with('error', 'Existem erros ao tentar aplicar as consequ�ncias ao user, por favor tente novamente...');
                }
                $report = new Report;
                $report->id_admin = Auth::guard('admin')->Id();
                $report->id_punished = intval($idUser);
                $report->date_report = Carbon::now();
                $report->reason = "Infração detetada por Admin";
                $report->id_reporter = User::all()->first()->value('id');

                $fast_ban = true;
            } else {
                $fast_ban = false;

                $validator = Validator::make($request->all(), [
                    'consequence' => 'required|in:suspend,ban,do_nothing',
                    'reportId' => 'required|integer|min:1|exists:reports,id',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->with('error', 'Existem erros ao tentar aplicar as consequ�ncias ao user, por favor tente novamente...');
                }
            }

            DB::beginTransaction();
            try {
                if (!$fast_ban)
                    $report = Report::findOrFail($request->input('reportId'));
                $user = User::findOrFail($idUser);

                $conseq = $request->input('consequence');

                if ($conseq === "suspend") {
                    $user->state_user = 'suspended';
                    $user->save();

                    $report->consequence = 'suspend';
                    $report->state_report = 'assumed';
                    $report->save();
                    DB::commit();
                    $message = "Utilizador suspendo com sucesso!";
                } else if ($conseq === "ban") {
                    $validator = Validator::make($request->all(), [
                        'punishement_span' => 'required|integer|min:1|regex:^[1-9][0-9]*^',
                        'observation_admin' => 'required|string|regex:/[A-Za-z\- ]+/i|min:1|max:1000',
                    ]);

                    if ($validator->fails()) {
                        return redirect()->back()
                            ->withErrors($validator)
                            ->with('error', 'Existem erros ao tentar aplicar as consequ�ncias ao user, por favor tente novamente...');
                    }

                    $reasonBan = $request->input('observation_admin');
                    $punishDays = $request->input('punishement_span');
                    $user->state_user = 'banned';
                    $user->save();

                    if ($fast_ban) {
                        $report->text_report = $reasonBan;
                    }
                    $report->consequence = 'ban';
                    $report->state_report = 'done';
                    $report->observation_admin = $reasonBan;
                    $report->date_begin_punishement = Carbon::now();
                    $report->punishement_span = intval($punishDays);
                    $report->save();

                    if ($fast_ban && $request->input('id_product') == null) {
                        $rep_user = new Report_user;
                        $rep_user->id_report = $report->id;
                        $rep_user->id_user = $user->id;
                        $rep_user->save();
                    } else if ($fast_ban && $request->input('id_product') != null) {
                        $rep_user = new Report_product;
                        $rep_user->id_report = $report->id;
                        $rep_user->id_product = $request->input('id_product');
                        $rep_user->save();
                    }
                    DB::commit();
                    $message = "Utilizador banido com sucesso!";
                } else {
                    $validator = Validator::make($request->all(), [
                        'observation_admin' => 'required|string|regex:/[A-Za-z\- ]+/i|min:1|max:1000',
                    ]);

                    if ($validator->fails()) {
                        return redirect()->back()
                            ->withErrors($validator)
                            ->with('error', 'Existem erros ao tentar aplicar as consequ�ncias ao user, por favor tente novamente...');
                    }

                    $reasonBan = $request->input('observation_admin');
                    $user->state_user = 'active';
                    $user->save();

                    $report->consequence = 'do_nothing';
                    $report->state_report = 'done';
                    $report->observation_admin = $reasonBan;
                    $report->date_begin_punishement = Carbon::now();
                    $report->punishement_span = 0;
                    $report->save();

                    DB::commit();
                    $message = "Report tratado com sucesso! Utilizador não foi suspenso/banido.";
                }

                return redirect()->back()->with('success', $message);
            } catch (ModelNotFoundException $err) {
                DB::rollBack();
                Log::channel('storeLog')->error('Reporte update informacao nao executada. Erro no  ' . $err->getModel());
                return response(null, 404);
            }
        } catch (ModelNotFoundException $e) {
            Log::channel('storeLog')->error('On model ' . $e->getModel());
        }
    }

    /**
     * Get admin register page
     */
    public function register()
    {
        if (Auth::guard('admin')->check()) {
            return view('pages.adminadd');
        } else {
            return redirect('/admin/login');
        }
    }

    /**
     * Create new admin
     * @param  \Illuminate\Http\Request  $request
     */
    public function create(Request $request)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect('/admin/login');
        } else {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|regex:/[A-Za-z0-9\-\.\_ ]*/i|max:255',
                'username' => 'required|string|alpha_dash|max:255|unique:admins',
                'email' => 'required|string|email|max:255|unique:admins',
                'password' => 'required|string|min:8|max:255|alpha_dash|regex:/[A-Za-z]+/|regex:/[0-9]/|confirmed',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator);
            }

            try {
                $admin = new Admin;
                $admin->username = $request->input('username');
                $admin->email = $request->input('email');
                $admin->name = $request->input('name');
                $admin->password = bcrypt($request->input('password'));
                $admin->save();

                $message = "Admin criado com sucesso!";
                return redirect()->back()->with('success', $message);
            } catch (ModelNotFoundException $e) {
                Log::channel('storeLog')->error('On model ' . $e->getModel());
            }
        }
    }

    /**
     * Search for users
     * @param  \Illuminate\Http\Request  $request
     */
    public function search(Request $request)
    {
        try {
            if (Auth::guard('admin')->check()) {

                if ($request->has('search')) {
                    $users = User::FTS($request->input('search'))->where('state_user', 'active')->paginate(10);

                    return view('pages.adminsearch', ['users' => $users, 'search' => $request->input('search')]);
                } else {
                    $users = User::select('id', 'username', 'email')->where('state_user', 'active')->paginate(10);
                    return view('pages.adminsearch', ['users' => $users]);
                }
            } else {
                return redirect('/admin/login');
            }
        } catch (ModelNotFoundException $e) {
            Log::channel('storeLog')->error('On model ' . $e->getModel());
        }
    }

    /**
     * Search for users AJAX
     * @param  \Illuminate\Http\Request  $request 
     */
    public function apiSearch(Request $request)
    {
        try {
            if (Auth::guard('admin')->check()) {

                if ($request->has('search')) {
                    $users = User::FTS($request->input('search'))->where('state_user', 'active')->paginate(10);
                    return $users;
                } else {
                    $users = User::select('id', 'username', 'email')->where('state_user', 'active')->paginate(10);
                    return view('pages.adminsearch', ['users' => $users]);
                }
            } else {
                return redirect('/admin/login');
            }
        } catch (ModelNotFoundException $e) {
            Log::channel('storeLog')->error('On model ' . $e->getModel());
        }
    }

    /*
     * Show reported users 
     * 
     */
    public function history()
    {
        try {
            if (!Auth::guard('admin')->check()) {
                return redirect('/admin/login');
            }
            $usersReported = Report_user::with(['user', 'report'])->get();
            return view('pages.adminhistory', [
                'usersReported' => $usersReported
            ]);
        } catch (ModelNotFoundException $e) {
            Log::channel('storeLog')->error('On model ' . $e->getModel());
        }
    }

    /**
     * Show the admin dashboard for handling users.
     *
     * @return \Illuminate\Http\Response
     */
    public function usersSearch(Request $request)
    {
        try {
            $search_query = $request->get('search');

            if (!empty($search_query)) {
                $users = User::FTS($search_query)->orderBy('name')->paginate(10);

                $users->withPath('?search=' . $search_query);
            } else {
                $users = User::orderBy('name')->paginate(10);
            }

            return view('pages.admin.users', ['users' => $users]);
        } catch (ModelNotFoundException $e) {
            Log::channel('storeLog')->error('On model ' . $e->getModel());
        }
    }

    /**
     * Remove a ban in a user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validated_data = $request->validate([
            'id' => 'required',
        ]);

        $id = $validated_data["id"];

        try {
            $banUser = Report_user::findOrFail($id);
            $banUser->delete();
            Log::channel('storeLog')->alert('Ban on user deleted');
            return response()->json([], 200);
        } catch (ModelNotFoundException $err) {
            Log::channel('storeLog')->error('On deleted ban user');
            return response()->json([], 404);
        }
    }
}