<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;
    
	public function __invoke(Request $request)
	{
        $this->validateEmail($request);
        
		// We will send the password reset link to this user. Once we have attempted
		// to send the link, we will examine the response then see the message we
		// need to show to the user. Finally, we'll send out a proper response.
		$response = $this->broker()->sendResetLink(
			$request->only('email')
        );
        
		return $response == Password::RESET_LINK_SENT
			? response()->json([
                'version' => config('api.version'),
                'status' => true,
                'message' => 'Reset link sent to your email.'
            ], 201)
			: response()->json([
                'version' => config('api.version'),
                'status' => false,
                'message' => 'Unable to send reset link'
            ], 401);
	}    
}