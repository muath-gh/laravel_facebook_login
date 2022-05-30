<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class FacebookLoginController extends Controller
{


    public function login(Request $request)
    {

        $token = $request->token;
        

        $providerUser = Socialite::driver("facebook")->userFromToken($token);
      
        $userProviderId = $providerUser->id;
      

        $user = User::where('provider_name', "facebook")->where('provider_id', $userProviderId)->first();
        if (!$user) {

            $user = User::create([
                "name" => $providerUser->name,
                "provider_name" => "facebook",
                "provider_id" => $userProviderId,
                "avatar" => "https://graph.facebook.com/v3.3/$userProviderId/picture?type=large&access_token=$token"
            ]);
        }

        $accessToken = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            "status" => "success",
            "access_token" => $accessToken
        ]);
    }
}
