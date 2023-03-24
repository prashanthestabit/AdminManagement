<?php

namespace Modules\AdminManagement\Http\Controllers;

use Exception;
use Gate;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Modules\AdminManagement\Http\Requests\StoreRoleRequest;
use Modules\AdminManagement\Http\Requests\UpdateRoleRequest;
use Modules\AdminManagement\Repositories\PermissionRepository;
use Modules\AdminManagement\Repositories\RoleRepository;

class RoleController extends Controller
{
    const FORBIDDEN = '403 Forbidden';

    const PERPAGE = 5;

    const ERROR = 'adminmanagement::auth.error';

    protected $role;

    public function __construct(RoleRepository $role)
    {
        $this->role = $role;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('access role'), Response::HTTP_FORBIDDEN, self::FORBIDDEN);

        try {
            $data = $this->role->paginate(self::PERPAGE,$request);

            return view('adminmanagement::roles.index', compact('data'))
                ->with('i', ($request->input('page', 1) - 1) * self::PERPAGE);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return Redirect::back()->with('error', __(self::ERROR).$e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        abort_if(Gate::denies('create role'), Response::HTTP_FORBIDDEN, self::FORBIDDEN);

        try {
            $permissions = (new PermissionRepository)->all();

            return view('adminmanagement::roles.create', compact('permissions'));
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return Redirect::back()->with('error', __(self::ERROR).$e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */
    public function store(StoreRoleRequest $request)
    {
        abort_if(Gate::denies('create role'), Response::HTTP_FORBIDDEN, self::FORBIDDEN);

        try {
            $this->role->create($request);

            return redirect()->route('admin.roles.index')
                           ->with('success', __('adminmanagement::auth.role.created'));
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return Redirect::back()->with('error', __(self::ERROR).$e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        abort_if(Gate::denies('edit role'), Response::HTTP_FORBIDDEN, self::FORBIDDEN);

        try {
            $role = $this->role->find($id);

            $permissions = (new PermissionRepository)->all();

            $rolePermissions = $role->permissions->pluck('id')->toArray();

            return view('adminmanagement::roles.edit', compact('role', 'permissions', 'rolePermissions'));
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return Redirect::back()->with('error', __(self::ERROR).$e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        abort_if(Gate::denies('edit role'), Response::HTTP_FORBIDDEN, self::FORBIDDEN);

        try {
            $this->role->update($id, $request);

            return redirect()->route('admin.roles.index')
                ->with('success', __('adminmanagement::auth.role.updated'));
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return Redirect::back()->with('error', __(self::ERROR).$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy(Request $request, $id)
    {
        abort_if(Gate::denies('delete role'), Response::HTTP_FORBIDDEN, self::FORBIDDEN);

        try {
            $this->role->delete($id);

            $paginator = $this->role->paginate(self::PERPAGE, $request);

            //Get page for redirect on same page after delete
            $redirectToPage = ($request->input('page') <= $paginator->lastPage())
               ? $request->input('page')
               : $paginator->lastPage();

            return redirect()->route('admin.roles.index', ['page' => $redirectToPage])
                            ->with('success', __('adminmanagement::auth.role.deleted'));
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return Redirect::back()->with('error', __(self::ERROR).$e->getMessage());
        }
    }
}
