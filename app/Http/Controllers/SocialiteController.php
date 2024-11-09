<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Function: googleLogin
     * Description: This function will redirect to Google
     * @param NA
     * @return void
     */
    public function googleLogin()
    {
        return Socialite::driver('google')->redirect();
    }

     /**
     * Function: googleAuthentication
     * Description: This function will redirect authenticate the user through the google account
     * @param NA
     * @return void
     */
    public function googleAuthentication()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate([
                'google_id' => $googleUser->id,
            ], [
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'password' => Hash::make('password@1234'),
                'google_id' => $googleUser->id
            ]);

            if($user){
                Auth::login($user);
                return redirect()->route('checkout');
            } else {
                return redirect('/')->with('error', 'Please try again.');
            }
           
        } catch (Exception $e) {
            return redirect('/')->with('error', 'Please try again.');
        }
       

        dd($googleUser);
    }

    
}
