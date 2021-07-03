<?php

namespace App\Http\Controllers;

use App\Report;
use App\Report_user;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{

    public function AuthRouteAPI(Request $request)
    {
        return $request->user();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            // if ($id === 0 or !$user or $user->state_user === 'inactive' or $user->state_user === 'banned' or $user->state_user === 'suspended') {
            //     return response()->view('errors.403');
            // }
        } catch (Exception $e) {
            return back()->with('error', 'Não foi possível mostrar o perfil');
        }

        return view('pages.users', ['user' => $user]);
    }

    /**
     * Get notifications
     * @param  int  $id user id
     * @return \Illuminate\Http\Response
     */
    public function notifications($id)
    {
        try {
            $user = User::findOrFail($id);
            // if ($id === 0 or !$user or $user->state_user === 'inactive' or $user->state_user === 'banned' or $user->state_user === 'suspended') {
            //     return response()->view('errors.403');
            // }
        } catch (Exception $e) {
            return back()->with('error', 'Não foi possível mostrar o perfil');
        }

        return view('pages.users', ['user' => $user]);
    }

    /**
     * Get history
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function history($id)
    {
        try {
            $user = User::findOrFail($id);
            // if ($id === 0 or !$user or $user->state_user === 'inactive' or $user->state_user === 'banned' or $user->state_user === 'suspended') {
            //     return response()->view('errors.403');
            // }
        } catch (Exception $e) {
            return back()->with('error', 'Não foi possível mostrar o perfil');
        }

        return view('pages.users', ['user' => $user]);
    }


    /**
     * Show the form for editing the specified resource.
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        try {
            if (!Auth::check()) {
                return response()->view('errors.401');
            }
            $user = Auth::user();
        } catch (Exception $e) {
            return back()->with('error', 'Não foi possível mostrar o perfil');
        }

        return view('pages.editProfile', ['user' => $user]);
    }

    /**
     * Store a newly created user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => 'bail',
            'email' => 'bail',
            'address' => 'bail',
            'id_postal' => 'bail',
            'phone_number' => 'bail',
            'photo' => 'bail|image',
            'description' => 'bail'
        ]);

        try {
            if ($request->input("name") != null) $user->name = $request->input("name");
            if ($request->input("email") != null) $user->email = $request->input("email");
            if ($request->input("address") != null) $user->address = $request->input("address");
            if ($request->input("id_postal") != null) $user->id_postal = $request->input("id_postal");
            if ($request->input("phone_number") != null) $user->phone_number = $request->input("phone_number");
            if ($request->input("description") != null) $user->description = $request->input("description");
            if ($request->file('photo'))
                $user->photo = $user->validateAndSave($request);

            try {
                $user->save();
            } catch (ModelNotFoundException $err) {
                Log::channel('storeLog')->error('User NOT edited');
                return response(null, 404);
            }

            $user->save();
        } catch (ModelNotFoundException $e) {
            Log::channel('storeLog')->error('On model ' . $e->getModel());
        }
        return redirect('users/' . $user->id)->withSuccess('Perfil editado com sucesso!');
    }


    /**
     * Report a user profile
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function report($id_user, Request $request)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        if (Auth::id() === $id_user)
            return redirect()->back()->with('error', 'Não se pode reportar a si mesmo...');

        $user = User::findOrFail($id_user);
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|regex:/[A-Za-z0-9\-\.\_ ]*/i|min:1|max:50',
            'textReport' => 'required|string|regex:/[A-Za-z0-9\-\.\_ ]*/i|min:1|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Existem erros no seu perfil, por favor tente novamente...');
        }

        try {
            $report = new Report;
            $report->id_admin = null;
            $report->id_punished = $id_user;
            $report->consequence = null;
            $report->state_report = 'assume';
            $report->observation_admin = null;
            $report->date_report = Carbon::now();
            $report->reason = $request->input('reason');
            $report->text_report = $request->input('textReport');
            $report->date_begin_punishement = null;
            $report->punishement_span = 0;
            $report->id_reporter = Auth::id();

            $report->save();

            $rep_user = new Report_user;
            $rep_user->id_report = $report->id;
            $rep_user->id_user = $id_user;
            $rep_user->save();

            $message = "Perfil reportado com sucesso!";

            return redirect()->back()->with('success', $message);
        } catch (ModelNotFoundException $e) {
            Log::channel('storeLog')->error('On model ' . $e->getModel());
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel()
    {
        try{           
            if(!Auth::check()){
            return response()->view('errors.401');
        }
            $user = Auth::user();
            $user->state_user = 'inactive';
            $user->save();
            Auth::logout();
            return redirect('/login')->with('success', 'Conta Cancelada com Sucesso. Os seus dados serão apagados nas próximas 24 horas',200);
        } catch (Exception $e) {
            return back()->with('error', 'Não foi possível cancelar a conta');
        }

    }
}