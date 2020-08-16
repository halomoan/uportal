<?php

namespace App\Http\Controllers\API;

use App\FAConfig as AppFAConfig;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\FAConfig;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $http = new \GuzzleHttp\Client;

        $request->validate([
            'username' => 'email|required',
            'password' => 'required|string|min:8|max:191',
        ]);

        try {
            $response = $http->post(config('services.passport.login_endpoint'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $request->username,
                    'password' => $request->password,
                ]
            ]);
            return $response->getBody();
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            if ($e->getCode() === 400) {
                return response()->json('Invalid Request. Please enter a username or a password.', $e->getCode());
            } else if ($e->getCode() === 401) {
                return response()->json('Your credentials are incorrect. Please try again', $e->getCode());
            }

            return response()->json('Something went wrong on the server.', $e->getCode());
        }
    }


    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json('Logged out successfully', 200);
    }

    public function falogin(Request $request)
    {

        $domain = config('app.domain');

        $formData = $request->validate([
            'phoneid' => 'required|string|min:8|max:20'
        ]);


        $domain = config('app.domain');
        $passwd = config('services.fabarcode.phone_password');

        $loginData['email'] = $formData['phoneid'] . '@' . $domain;
        $loginData['password'] = $passwd;


        if (!Auth::attempt($loginData)) {
            return response(['msg' => 'Phone ID has not been registered']);
        }

        $authenticated_user = Auth::user();
        $user = User::find($authenticated_user->id);

        $token = $user->createToken('authToken')->accessToken;

        $group = $user->groups()->first();
        if ($group === null) {
            return response(['msg' => 'Phone ID is not part of any group']);
        }

        $faconfig = FAConfig::find($group->id);

        return response([
            'sub1len' => $faconfig->sub1len,
            'sub2len' => $faconfig->sub2len, 'sub3len' => $faconfig->sub3len, 'runlen' => $faconfig->runlen, 'access_token' => $token
        ]);
    }
}
