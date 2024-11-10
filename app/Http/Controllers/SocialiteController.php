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
     * Function: authProviderRedirect
     * Description: This function will redirect to given auth provider social
     * @param NA
     * @return void
     */
    public function authProviderRedirect($provider)
    {
        if($provider){
            return Socialite::driver($provider)->redirect();
        }
        
        abort(404);
    }

     /**
     * Function: socialAuthentication
     * Description: This function will redirect authenticate the user through the given social account
     * @param NA
     * @return void
     */
    public function socialAuthentication($provider)
    {
        try {

            if($provider){
                $socialUser = Socialite::driver($provider)->user();
                
                if(!empty($socialUser)){
                    $password = $provider.'@123';
                    $email = $provider.'.'.$socialUser->email;
                    $user = User::updateOrCreate([
                        'auth_provider_id' => $socialUser->id,
                    ], [
                        'name' => $socialUser->name,
                        'email' => trim($email),
                        'password' => Hash::make(trim($password)),
                        'auth_provider' => $provider,
                        'auth_provider_id' => $socialUser->id
                    ]);
        
                    if($user){
                        Auth::login($user);
                        return redirect()->route('checkout');
                    } else {
                        return redirect('/')->with('error', 'Please try again.');
                    }
                } else {
                    return redirect('/')->with('error', 'Please try again.');
                } 
            }
            
            abort(404);
            
           
        } catch (Laravel\Socialite\Two\InvalidStateException $e) {
            dd($e);
            return redirect('/')->with('error', 'Please try again.');
        }
       

        dd($googleUser);
    }

    
}
