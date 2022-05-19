<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function login(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required | string | email | max:255',
            'password' => 'required | between:8, 255'
        ]);

        if ($validator->fails()) {
            # code...
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $passwordGrantClient = Client::Where('password_client', 1)->first();

        $data = [
            'grant_type' => 'password',
            'client_id' => $passwordGrantClient->id,
            'client_secret' => $passwordGrantClient->secret,
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '*'
        ];

        $tokenRequest = Request::create('/oauth/token', 'post', $data);
        $tokenResponse = app()->handle($tokenRequest);
        $contentString = $tokenResponse->getContent();
        $tokenContent = json_decode($contentString, true);

        if (!empty($tokenContent['access_token'])) {
            # code...
            return $tokenResponse;
        }

        return response()->json([
            'error' => "les informations d'identification invalides"
        ], 401);

    }

    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required | string | max:255',
            'email' => 'required | string | max:255 | email | unique:users',
            'password' => 'required | between:8, 255 | confirmed',
        ]);

        if ($validator->fails()) {
            # code...
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        if (!$user) {
            # code...
            return response()->json(['success' => false, 'message' => "échec de l'enregistrement"], 500);
        }

        return response()->json(['errors' => true, 'message' => "inscription réussie"], 200);
    }
}
