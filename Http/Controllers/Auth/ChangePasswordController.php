<?php

namespace Modules\AdminManagement\Http\Controllers\Auth;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Modules\AdminManagement\Http\Requests\ChangePasswordRequest;
use Modules\AdminManagement\Repositories\AuthRepository;

class ChangePasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        return view('adminmanagement::profile.changePassword');
    }

    /**
     * Update the change password resource in database.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function updatePassword(ChangePasswordRequest $request)
    {
        try {
            $auth = (new AuthRepository());
            // Check if the old password matches the user's current password
            if (! Hash::check($request->old_password, auth()->user()->password)) {
                return back()
                      ->withErrors(['old_password' => __('adminmanagement::auth.password_incorrect')])
                      ->withInput();
            }

            // Update the user's password
            $auth->authUpdate([
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('admin.dashboard')
                    ->with('success', __('adminmanagement::auth.password_changed'));
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return Redirect::back()->with('error', __('adminmanagement::auth.error').$e->getMessage());
        }
    }
}
