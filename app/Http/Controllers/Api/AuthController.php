<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Exception;


class AuthController extends Controller
{

    /**
     * Log in the user.
     */
    public function login(Request $req)
    {

        if (class_exists(\Modules\Crm\Events\Auth\BeforeLoginAttempt::class)) {
            event(new \Modules\Crm\Events\Auth\BeforeLoginAttempt($req->only('email')));
        }


        $validator = Validator::make($req->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
            'remember' => 'sometimes|boolean',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $user = User::where('email', $req->email)->first();


        if (!$user || !Hash::check($req->password, $user->password)) {
            return response()->json(['error' => trans('auth.login_failed')], 401);
        }


        if (!$user->email_verified_at) {
            return response()->json(['error' => trans('auth.email_not_verified')], 401);
        }


        if (!$user->active) {
            return response()->json(['error' => trans('auth.user_not_active')], 401);
        }

        
        // --- Efter succesfuld validering ---
        if (class_exists(\Modules\Crm\Events\Auth\UserLoginSuccess::class)) {
            event(new \Modules\Crm\Events\Auth\UserLoginSuccess($user));
        }


        $remember = $req->boolean('remember', true);

        Auth::login($user, $remember);

        $user->update(['lastLoggedIn' => now()]);


        $token = $user->createToken($user->email . '-' . now())->plainTextToken;


        // --- Efter alt er færdigt ---
        if (class_exists(\Modules\Crm\Events\Auth\UserLoggedIn::class)) {
            event(new \Modules\Crm\Events\Auth\UserLoggedIn($user, $token));
        }


        if ($req->filled('include')) {
            $relations = explode(',', $req->input('include'));
            $user->load($relations);
        }


        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);

        
    }


    /**
     * Log out the user.
     */
    public function logout(Request $request)
    {

        try {

            $user = Auth::user();


            if ($user) {

                if ($request->boolean('logoutAllDevices')) {
                    $user->tokens()->delete();
                }

                
                Auth::logout();


                if ($request->boolean('logoutOtherDevices') && $request->filled('password')) {
                    Auth::logoutOtherDevices($request->input('password'));
                }

            }



            return response()->json(['message' => trans('auth.logout_success')], 200);



        } catch (Exception $e) {


            return response()->json(['error' => trans('auth.logout_failed')], 500);


        }

    }


    /**
     * Send a password reset link to the user's email.
     */
    public function sendResetLink(Request $req)
    {

        $req->validate([
            'email' => 'required|email|exists:users,email',
            'return' => 'sometimes|url',
        ]);


        $user = User::where('email', $req->email)->first();

        $token = Str::random(64);

        $redirect = $req->get('return', config('redirects.RESET_PASSWORD_REDIRECT', url('/reset-password/success')));


        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email],
            ['token' => $token, 'created_at' => now(), 'redirect' => $redirect]
        );


        if (class_exists(\Modules\Email\Services\ForgotPasswordService::class)) {
            app(\Modules\Email\Services\ForgotPasswordService::class)->send([
                'first_name' => $user->first_name,
                'email' => $user->email,
                'token' => $token,
                'redirect' => $redirect,
            ]);
        }


        return response()->json(['message' => trans('auth.password_reset_email_sent')]);

    }


    /**
     * Update password.
     */
    public function update(Request $req)
    {
        $req->validate([
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $resetData = DB::table('password_resets')->where('token', $req->token)->first();

        if (!$resetData) {
            return response()->json(['error' => trans('auth.invalid_token')], 422);
        }


        User::where('email', $resetData->email)->update([
            'password' => Hash::make($req->password)
        ]);


        DB::table('password_resets')->where('token', $req->token)->delete();


        return response()->json([
            'message' => trans('auth.password_reset_success'),
            'redirect' => $resetData->redirect
        ], 200);

    }

    /**
     * Resend activation email.
     */
    public function resendActivationEmail(Request $req)
    {


        $validator = Validator::make($req->all(), ['email' => 'required|email']);


        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);


        $user = User::where('email', $req->email)->first();

        if (!$user) return response()->json(['error' => trans('auth.user_not_exists')], 404);


        $sent = class_exists(\Modules\Email\Services\VerificationService::class)
            ? app(\Modules\Email\Services\VerificationService::class)->send($user)
            : false;

        return response()->json([
            'message' => trans('auth.activation_email_sent'),
            'email_queued' => (bool) $sent,
        ], 200);

    }


    /**
     * Resend invitation email.
     */

    public function resendInvitation(Request $req)
    {

        $validator = Validator::make($req->all(), ['email' => 'required|email']);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);


        $user = User::where('email', $req->email)->first();
        if (!$user) return response()->json(['error' => trans('auth.user_not_exists')], 404);


        if (class_exists(\Modules\Email\Services\InviteService::class)) {
            app(\Modules\Email\Services\InviteService::class)->send($user);
        }
        
        return response()->json(['message' => trans('auth.invitation_email_sent')], 200);

    }

    /**
     * Refresh token.
     */
    public function refreshToken(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => trans('auth.unauthenticated')], 401);
        }

        return response()->json([
            'token' => $request->bearerToken(),
            'token_type' => 'Bearer',
        ]);
    }
}
