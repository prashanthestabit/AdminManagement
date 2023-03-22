<?php

namespace Modules\AdminManagement\Repositories;

use Modules\AdminManagement\Interface\PermissionInterface;
use Spatie\Permission\Models\Permission;

/* Class PermissionRepository.
 * This class is responsible for handling database operations related to permission.
 */
class PermissionRepository implements PermissionInterface
{
    /**
    * Get All Permission pagination
    * @param integer $perPage
    *
    * @return \Spatie\Permission\Models\Permission
    */
    public function paginate($perPage)
    {
        return Permission::orderBy('id', 'DESC')->paginate($perPage);
    }

    /**
    * Get All Permission data
    *
    *
    * @return \Spatie\Permission\Models\Permission
    */
    public function all()
    {
        return Permission::all();
    }

    /**
     * Find By Id
     */
    public function find($id)
    {
        return Permission::find($id);
    }


    /**
    * Update permission
    * @param integer $id
    * @param $request
    * @return \Spatie\Permission\Models\Permission
    */
    public function update($id, $request)
    {
        $permission = Permission::find($id);
        $permission->name = $request->name;
        $permission->save();

        return $permission;
    }

    /**
    * Create permission
    * @param integer $perPage
    *
    * @return \Spatie\Permission\Models\Permission
    */
    public function create($request)
    {
        return Permission::create(['name' => $request->name]);
    }

    /**
    * Delete Permission By id
    *
    * @param integer $id
    * @return \Spatie\Permission\Models\Permission
    */
    public function delete($id)
    {
        return Permission::find($id)->delete();
    }
}
