<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function login() {

    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required | string | max:255',
            'email' => 'required | string | email | max:255 | unique:users',
            'password' => 'required | between:8, 255 | confirmed'
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

        return response()->json(['success' => true, 'message' => "inscription réussie"], 200);
    }
}
