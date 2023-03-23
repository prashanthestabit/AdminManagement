<?php

namespace Modules\AdminManagement\Http\Controllers\Auth;

use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Modules\AdminManagement\Http\Requests\ResetPasswordRequest;
use Modules\AdminManagement\Repositories\AuthRepository;

class ResetPasswordController extends Controller
{
    /**
     * Show the form for reset password.
     *
     * @return response
     */
    public function showResetPasswordForm($token)
    {
        return view('adminmanagement::auth.forgetPasswordLink', ['token' => $token]);
    }

    /**
     * Handle Reset password Form Request
     *
     * @return response()
     */
    public function submitResetPasswordForm(ResetPasswordRequest $request)
    {
        try {
            $auth = (new AuthRepository());

            $updatePassword = $auth->getPasswordReset([
                'email' => $request->email,
                'token' => $request->token,
            ]);

            if (! $updatePassword) {
                return back()->withInput()->with('error', __('adminmanagement::auth.invalid_token'));
            }

            // Update user password
            $auth->update(['email' => $request->email], ['password' => Hash::make($request->password)]);

            //Delete password reset token
            $auth->deletePasswordReset(['email' => $request->email]);

            return redirect()->route('login')->with('message', 'Your password has been changed!');
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return Redirect::back()->with('error', __('adminmanagement::auth.error').$e->getMessage());
        }
    }
}
