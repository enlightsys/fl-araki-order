<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\Member;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailRegist;

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
    protected $redirectTo = RouteServiceProvider::HOME;

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
            'name' => ['required', 'string', 'max:255'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'zip' => ['required', 'string', 'max:8'],
            'pref_id' => ['required', 'numeric'],
            'city' => ['required', 'string', 'max:255'],
            'tel' => ['required', 'string', 'max:13'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:members'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => "名前を入力してください。",
            'zip.required' => "郵便番号を入力してください。",
            'pref_id.required' => "都道府県を選択してください。",
            'city.required' => "市区町村を入力してください。",
            'tel.required' => "電話番号を入力してください。",
            'email.required' => "メールアドレスを入力してください。",
            'email.unique' => "そのメールアドレスは既に登録されています。",
            'email.email' => "メールアドレスを正しく入力してください。",
            'password.required' => "パスワードを入力してください。",
            'password.confirmed' => "パスワードが確認用の値と一致しません。",
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\Member
     */
    protected function create(array $data)
    {
        Mail::to($data['email'])->send(new MailRegist(json_decode(json_encode($data))));

        return Member::create([
            'name' => $data['name'],
            'contact_name' => $data['contact_name'],
            'zip' => $data['zip'],
            'pref_id' => $data['pref_id'],
            'city' => $data['city'],
            'address' => $data['address'],
            'tel' => $data['tel'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'bill_enabled' => 1,
        ]);
    }
}
