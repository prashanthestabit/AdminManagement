<?php

namespace Modules\AdminManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Exception;
use Gate;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Modules\AdminManagement\Http\Requests\StoreUserRequest;
use Modules\AdminManagement\Http\Requests\UpdateUserRequest;
use Modules\AdminManagement\Repositories\AuthRepository;
use Modules\AdminManagement\Repositories\PermissionRepository;
use Modules\AdminManagement\Repositories\RoleRepository;

class UserController extends Controller
{
    const FORBIDDEN = '403 Forbidden';

    const PERPAGE = 5;

    const ERROR = 'adminmanagement::auth.error';
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('access user'), Response::HTTP_FORBIDDEN, self::FORBIDDEN);

        try {
            $data = User::orderBy('id', 'DESC')->paginate(self::PERPAGE);
            return view('adminmanagement::users.index', compact('data'))
                ->with('i', ($request->input('page', 1) - 1) * self::PERPAGE);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return Redirect::back()->with('error', __(self::ERROR) . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        abort_if(Gate::denies('create user'), Response::HTTP_FORBIDDEN, self::FORBIDDEN);

        try {
            $roles = (new RoleRepository)->all()->pluck('name');

            $permissions = (new PermissionRepository)->all();

            return view('adminmanagement::users.create', compact('roles', 'permissions'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return Redirect::back()->with('error', __(self::ERROR) . $e->getMessage());
        }

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(StoreUserRequest $request)
    {
        abort_if(Gate::denies('create user'), Response::HTTP_FORBIDDEN, self::FORBIDDEN);

        try {
                $user = (new AuthRepository())->create($request->all());

                $user->assignRole($request->input('roles'));

                // Adding permissions to a user
                $user->givePermissionTo($request->input('permissions'));

            return redirect()->route('admin.users.index')
                            ->with('success', __('adminmanagement::auth.user.created'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return Redirect::back()->with('error', __(self::ERROR) . $e->getMessage());

        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        abort_if(Gate::denies('access user'), Response::HTTP_FORBIDDEN, self::FORBIDDEN);
        try {
            $user = (new AuthRepository())->find($id);
            $userRole = $user->roles->pluck('name')->all();
            return view('adminmanagement::users.show', compact('user', 'userRole'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return Redirect::back()->with('error', __(self::ERROR) . $e->getMessage());

        }

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('edit user'), Response::HTTP_FORBIDDEN, self::FORBIDDEN);

        try {
        $user = (new AuthRepository())->find($id);

        $roles = (new RoleRepository)->all()->pluck('name');

        $permissions = (new PermissionRepository)->all();

        $userRole = $user->roles->pluck('name')->all();

        $userPermission = $user->permissions->pluck('name')->all();

        return view('adminmanagement::users.edit',
            compact('user', 'roles', 'userRole', 'permissions', 'userPermission'));

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return Redirect::back()->with('error', __(self::ERROR) . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateUserRequest $request, $id)
    {
        abort_if(Gate::denies('edit user'), Response::HTTP_FORBIDDEN, self::FORBIDDEN);

        try {
            $auth = (new AuthRepository());

            $auth->update(['id'=>$id], $request->only('name', 'email'));

            $user = $auth->find($id);

            DB::table('model_has_roles')->where('model_id', $id)->delete();

            $user->assignRole($request->input('roles'));

            if ($request->input('permissions')) {
                $user->givePermissionTo($request->input('permissions'));
            }


            return redirect()->route('admin.users.index')
                        ->with('success', __('adminmanagement::auth.user.updated'));

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
    public function destroy(Request $request, $id)
    {
        abort_if(Gate::denies('delete user'), Response::HTTP_FORBIDDEN, self::FORBIDDEN);

        try {
             (new AuthRepository())->delete($id);

             $paginator = User::paginate(self::PERPAGE);

             //Get page for redirect on same page after delete
             $redirectToPage = ($request->input('page') <= $paginator->lastPage())
                ? $request->input('page')
                :$paginator->lastPage();

            return redirect()->route('admin.users.index', ['page'=> $redirectToPage])
                            ->with('success', __('adminmanagement::auth.user.deleted'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return Redirect::back()->with('error', __(self::ERROR) . $e->getMessage());
        }
    }
}
