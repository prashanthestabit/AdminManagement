<?php

namespace Modules\AdminManagement\Http\Controllers\Auth;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\AdminManagement\Repositories\AuthRepository;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Modules\AdminManagement\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{

    const ERROR = 'adminmanagement::auth.error';

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('adminmanagement::profile.index');
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(ProfileRequest $request)
    {
        try {
            $userId = Auth::user()->id;
            $auth = (new AuthRepository());

            $auth->update(['id'=>$userId], $request->only('name', 'email'));

            return redirect()->route('profile.show')
            ->with('success', __('adminmanagement::auth.profile.updated'));

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return Redirect::back()->with('error', __(self::ERROR) . $e->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
