<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    //
    public function reset(Request $request) {
        $validator = Validator::make($request->all(),[
            'email' => 'required | email | string | max:255',
            'password' => 'required | confirmed | between:8, 255',
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            # code...
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $response = $this->broker()->reset(
            $this->credentials($request), function($user, $password) {
                $this->resetPassword($user, $password);
            });

        return $response == Password::PASSWORD_RESET ? $this->sendResetResponse($request, $response) : $this->sendResetFailedResponse($request, $response);
    }

    public function credentials(Request $request) {
        return $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
    }

    protected function resetPassword($user, $password) {
        $this->setUserPassword($user, $password);
        $user->setRememberToken(Str::random(60));
        $user->save();
        event(new PasswordReset($user));
    }

    protected function setUserPassword($user, $password) {
        $user->password = Hash::make($password);
    }

    public function broker() {
        return Password::broker();
    }

    protected function sendResetResponse(Request $request, $response) {
        return $response->json([
            "message" => "la réinitialisation du mot de passe a réussi",
            "response" => $response
        ], 200);
    }

    protected function sendResetFailedResponse(Request $request, $response) {
        return $response->json([
            "message" => "échec de la réinitialisation du mot de passe",
            "response" => $response
        ], 500);
    }
}
