<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        $tokenRequest = Request::create('/oauth/token', 'post', [
            'grant_type' => 'password',
            'client_id' => config('auth.client_id'),
            'client_secret' => config('auth.client_secret'),
            'username' => request('username'),
            'password' => request('password'),
            'scope' => '*',
        ]);

        $response = app()->handle($tokenRequest);
        $responseJson = json_decode($response->getContent());
        if ($response->status() === 200) {
            return response()->json([
                'status' => 1,
                'message' => 'success',
                'access_token' => $responseJson->access_token,
                'refresh_token' => $responseJson->refresh_token
            ]);
        } elseif ($responseJson->error === 'invalid_grant') {
            return response()->json([
                'status' => 0,
                'message' => 'Credential is invalid'
            ]);
        } elseif ($responseJson->error === 'invalid_request'){
            return response()->json([
                'status' => 0,
                'message' => 'Request is invalid'
            ]);
        } else {
            return response()->json([
               'status' => 0,
               'message' => $responseJson->message
            ]);
        }
    }
}
