<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;

class GoogleController extends Controller
{
    public function redirectToGoogle(){
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();


    }
    public function handleGoogleCallback(){
        
        try {
            $user = Socialite::driver('google')->user();
            $finduser = User::where('google_id', $user->id)->first();
            if($finduser)
            {
                Auth::login($finduser);
                return redirect()->intended('home');
       
            }
            else
            {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'password' => encrypt('12345678'),
                ]);
                Auth::login($newUser);
                return redirect()->intended('home');
            }
        } 
        catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
