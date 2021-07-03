<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Postal;
use App\Rules\PostalCode;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|alpha_dash|regex:/[A-Za-z]+/|regex:/[0-9]/|confirmed',
            'phone_number' => 'required|digits:9',
            'address' => 'required|string|max:255',
            'id_postal' => ['required', 'postal_code:PT', new PostalCode],
            'birth_date' => 'required|date|before:-18 years',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $id_postal = intval($data['id_postal']);
        $postal = Postal::where('postal_code', $id_postal)->first();
        $data['id_postal'] = $postal['id_postal'];

        
        return User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'phone_number' => $data['phone_number'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'address' => $data['address'],
            'id_postal' => $data['id_postal'],
            'birth_date' => $data['birth_date'],
        ]);

    }
}
