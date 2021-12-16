<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->stateless()->user();
            $findUser = User::where('facebook_id', $user->id)->first();

            if($findUser){

                Auth::login($findUser);

            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'facebook_id'=> $user->id,
                    'password' => Hash::make('123456'),
                ]);

                Auth::login($newUser);

            }
            return redirect()->route('dashboard');

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
