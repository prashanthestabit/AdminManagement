<?php

namespace Modules\AdminManagement\Repositories;

use Modules\AdminManagement\Interface\RoleInterface;
use Spatie\Permission\Models\Role;

/* Class RoleRepository.
 * This class is responsible for handling database operations related to roles.
 */
class RoleRepository implements RoleInterface
{
    public function find($id)
    {
        return Role::find($id);
    }

    /**
     * Get All Role data
     *
     *
     * @return \Spatie\Permission\Models\Role
     */
    public function all()
    {
        return Role::all();
    }

    /**
     * Get All Role pagination
     *
     * @param  int  $perPage
     * @return \Spatie\Permission\Models\Role
     */
    public function paginate($perPage)
    {
        return Role::with('permissions')->orderBy('id', 'DESC')->paginate($perPage);
    }

    /**
     * Create Role with permission
     *
     * @param  int  $perPage
     * @return \Spatie\Permission\Models\Role
     */
    public function create($request)
    {
        $role = Role::create(['name' => $request->name]);
        $permissions = $request->permissions;

        $role->syncPermissions($permissions);

        return $role;
    }

    /**
     * Update Role with permission
     *
     * @param  int  $perPage
     * @return \Spatie\Permission\Models\Role
     */
    public function update($id, $request)
    {
        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();

        $permissions = $request->input('permissions', []);

        $role->syncPermissions($permissions);

        return $role;
    }

    /**
     * Delete Role By id
     *
     * @param  int  $id
     * @return \Spatie\Permission\Models\Role
     */
    public function delete($id)
    {
        return Role::find($id)->delete();
    }
}
