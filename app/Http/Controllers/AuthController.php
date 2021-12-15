<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\UserVerify;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function registration()
    {
        return view('auth.registration');
    }

    public function postLogin(LoginRequest $request): RedirectResponse
    {
        $remember_me = $request->has('remember') ? true : false;
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials,$remember_me)) {
            return redirect()->route('dashboard');
        }
        toastr()->error('Opps! You have entered invalid credentials');
        return redirect()->route('login');
    }

    public function postRegistration(RegisterRequest $request): RedirectResponse
    {
        $user=$this->create($request->all());
        $token = Str::random(64);

        UserVerify::create([
            'user_id' => $user->id,
            'token' => $token
        ]);
        Mail::send('email.emailVerificationEmail', ['token' => $token], function($message) use($request){
            $message->from(env('MAIL_USERNAME'));
            $message->to($request->email);
            $message->subject('Email Verification Mail');
        });
        toastr()->success('Great! Register Successfully !')
            ->info('We have send email confirmation to your email . Please check your email');
        return redirect()->route('login');
    }

    public function dashboard()
    {
        toastr()->success('You have successfully logged in');
        return view('dashboard');
    }

    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }


    public function logout(Request $request): RedirectResponse
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Auth::logout();

        return redirect()->route('login');
    }

    public function verifyAccount($token): RedirectResponse
    {
        $verifyUser = UserVerify::where('token', $token)->first();

        $message = 'Sorry your email cannot be identified.';

        if(!is_null($verifyUser) ){
            $user = $verifyUser->user;

            if(!$user->is_email_verified) {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->email_verified_at = Carbon::now();
                $verifyUser->user->save();
                $message = "Your e-mail is verified. You can now login.";
            } else {
                $message = "Your e-mail is already verified. You can now login.";
            }
        }
        toastr()->info($message);
        return redirect()->route('login');
    }
}
