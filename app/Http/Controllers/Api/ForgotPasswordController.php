<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    //
    public function sendResetLinkEmail(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required | string | email | max:255'
        ]);

        if ($validator->fails()) {
            # code...
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }

    public function broker() {
        return Password::broker();
    }

    protected function sendResetLinkResponse(Request $request, $response) {
        return response()->json(["message" => 'Email envoyé', 'response' => $response], 200);
    }

    protected function sendResetLinkFailedResponse(Request $request, $response) {
        return response()->json(["error" => "pas un tel utilisateur", 'response' => $response], 500);
    }
}
