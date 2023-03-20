<?php

namespace Modules\AdminManagement\Http\Controllers\Auth;

use App\Models\User;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Modules\AdminManagement\Http\Requests\ForgetPasswordRequest;
use Modules\AdminManagement\Http\Requests\PostLoginRequest;
use Modules\AdminManagement\Http\Requests\PostRegistrationRequest;
use Illuminate\Support\Str;
use Modules\AdminManagement\Repositories\AuthRepository;
use Session;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('adminmanagement::auth.login');
    }

    /**
     * User Registration Form
     *
     * @return response()
     */
    public function registration()
    {
        return view('adminmanagement::auth.registration');
    }

     /**
     * Forgot password form
     * @return response()
     */
      public function showForgetPasswordForm()
      {
         return view('adminmanagement::auth.forgetPassword');
      }


      /**
       * Write code on Method
       *
       * @return response()
       */
      public function submitForgetPasswordForm(ForgetPasswordRequest $request)
      {
          $token = Str::random(64);

          $auth = new AuthRepository();

          $rs = $auth->savePasswordReset($request->email, $token);

         if ($rs) {
          /// $auth->sendMail($token, $request, __('adminmanagement::auth.reset_password'));
         }

          return back()->with('message', __('adminmanagement::auth.mailed_message'));
      }

    /**
     * Hendle Login
     *
     * @return response()
     */
    public function postLogin(PostLoginRequest $request)
    {
       try {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard')
                        ->withSuccess('You have Successfully loggedin');
        }

        return redirect()->route('admin.dashboard');
     } catch (Exception $e) {
        Log::error($e->getMessage());
        return Redirect::back()->with('error', __('adminmanagement::auth.error') . $e->getMessage());
     }
    }

    /**
     * Handle Register Request
     *
     * @return response()
     */
    public function postRegistration(PostRegistrationRequest $request)
    {
        try {
            $data = $request->all();
            $check = (new AuthRepository())->create($data);

            if ($check) {
                $credentials = $request->only('email', 'password');
                if (Auth::attempt($credentials)) {
                    return redirect()->route('admin.dashboard');
                }
            }

            return Redirect::back();

        } catch (Exception $e)
        {
          Log::error($e->getMessage());
          return Redirect::back()->with('error', __('adminmanagement::auth.error') . $e->getMessage());
        }
    }

    /**
     * Logout
     *
     * @return response()
     */
    public function logout() {
        Session::flush();
        Auth::logout();

        return redirect()->route('login');
    }
}
