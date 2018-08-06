<?php

namespace App\Http\Controllers\Auth;

use App\Cuy;
use App\User;
use App\Http\Controllers\Controller;
use App\Year;
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
    protected $redirectTo = '/dashboard';

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
            'username' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'fname' => 'required|max:255',
            'lname' => 'required|max:255',
            'role' => 'required|max:7',
            'phone' => 'max:15',
            'mobile' => 'max:15',
        ],[
            'username.max' => 'فیلد نام کاربری نمی تواند بیشتر از 255 کاراکتر باشد.',
            'username.required' => 'فیلد نام کاربری باید پر شود.',
            'username.unique' => 'این نام کاربری قبلا ثبت شده است.',
            'email.required' => 'فیلد ایمیل کاربری باید پر شود.',
            'email.email' => 'فیلد ایمیل را به فرمت ایمیل وارد کنید.',
            'email.max' => 'فیلد ایمیل نمی تواند بیشتر از 255 کاراکتر باشد.',
            'password.required' => 'فیلد رمز عبور باید پر شود.',
            'password.confirmed' => 'تکرار رمز عبور اشتباه می باشد.',
            'password.min' => 'رمز عبور نمی تواند کمتر از 6 کاراکتر باشد.',
            'fname.required' => 'فیلد نام باید پر شود.',
            'fname.max' => 'فیلد نام نمی تواند بیشتر از 255 کاراکتر باشد.',
            'lname.required' => 'فیلد نام خانوادگی باید پر شود.',
            'lname.max' => 'فیلد نام خانوادگی نمی تواند بیشتر از 255 کاراکتر باشد.',
            'role.max' => 'کاربر فقط می تواند معلم یا دانش آموز باشد.',
            'phone.max' => 'فیلد شماره تلفن نمی تواند بیشتر از 15 کاراکتر باشد.',
            'mobile.max' => 'فیلد شماره موبایل نمی تواند بیشتر از 15 کاراکتر باشد.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $year = Year::first();
        if (count(Year::all())<1){
            $year = Year::create([
                'year' => '1390-91'
            ]);
        }

        $user =  User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'fname' => $data['fname'],
            'lname' => $data['lname'],
            'activated' => 0,
            'role' => $data['role'],
            'birthday' => $data['birthday'],
            'year_id' => $year->id,
            'address' => $data['address'],
            'phone' => $data['phone'],
            'mobile' => $data['mobile'],
            'photo' => 'male.png',
        ]);


        Cuy::create([
            'user_id' => $user->id,
            'year_id' => Year::first()->id,
            'clase' => 0,
            'role' => 'student',
        ]);


        return $user;
    }
}
