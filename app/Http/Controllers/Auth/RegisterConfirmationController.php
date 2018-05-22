<?php
namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;

class RegisterConfirmationController extends Controller
{
    /**
     * Confirm a user's email address.
     */
    public function __invoke()
    {
        $user = User::where('confirmation_token', request('token'))->first();

        if ($user) {

            $user->confirm();

            return response()->json([
                'version' => config('api.version'),
                'status' => true,
                'message' => __('auth.register_confirmation')
            ], 200);            
        }

        return response()->json([
            'version' => config('api.version'),
            'status' => false,
            'message' => __('auth.error_register_confirmation')
        ], 404);
    }
}