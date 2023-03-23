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
use Modules\AdminManagement\Http\Requests\StorePermissionRequest;
use Modules\AdminManagement\Http\Requests\UpdatePermissionRequest;
use Modules\AdminManagement\Repositories\PermissionRepository;

class PermissionController extends Controller
{
    const FORBIDDEN = '403 Forbidden';

    const PERPAGE = 5;

    const ERROR = 'adminmanagement::auth.error';

    protected $permission;

    public function __construct(PermissionRepository $permission)
    {
        $this->permission = $permission;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('access permission'), Response::HTTP_FORBIDDEN, self::FORBIDDEN);

        try {
            $data = $this->permission->paginate(self::PERPAGE);

            return view('adminmanagement::permissions.index', compact('data'))
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
        abort_if(Gate::denies('create permission'), Response::HTTP_FORBIDDEN, self::FORBIDDEN);

        try {
            return view('adminmanagement::permissions.create');
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
    public function store(StorePermissionRequest $request)
    {
        abort_if(Gate::denies('create permission'), Response::HTTP_FORBIDDEN, self::FORBIDDEN);

        try {
            $this->permission->create($request);

            return redirect()->route('admin.permissions.index')
                           ->with('success', __('adminmanagement::auth.permission.created'));
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
        abort_if(Gate::denies('edit permission'), Response::HTTP_FORBIDDEN, self::FORBIDDEN);

        try {
            $permission = $this->permission->find($id);

            return view('adminmanagement::permissions.edit', compact('permission'));
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
    public function update(UpdatePermissionRequest $request, $id)
    {
        abort_if(Gate::denies('edit permission'), Response::HTTP_FORBIDDEN, self::FORBIDDEN);

        try {
            $this->permission->update($id, $request);

            return redirect()->route('admin.permissions.index')
                ->with('success', __('adminmanagement::auth.permission.updated'));
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
        abort_if(Gate::denies('delete permission'), Response::HTTP_FORBIDDEN, self::FORBIDDEN);

        try {
            $this->permission->delete($id);

            $paginator = $this->permission->paginate(self::PERPAGE);

            //Get page for redirect on same page after delete
            $redirectToPage = ($request->input('page') <= $paginator->lastPage())
               ? $request->input('page')
               : $paginator->lastPage();

            return redirect()->route('admin.permissions.index', ['page' => $redirectToPage])
                            ->with('success', __('adminmanagement::auth.permission.deleted'));
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return Redirect::back()->with('error', __(self::ERROR).$e->getMessage());
        }
    }
}
