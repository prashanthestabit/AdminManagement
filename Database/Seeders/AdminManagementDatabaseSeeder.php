<?php

namespace Modules\AdminManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminManagementDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

          // Reset cached roles and permissions
          app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

          // create permissions
          Permission::create(['name' => 'edit user']);
          Permission::create(['name' => 'delete user']);
          Permission::create(['name' => 'create user']);
          Permission::create(['name' => 'access user']);

          // create roles and assign created permissions

          // this can be done as separate statements
          $role = Role::create(['name' => 'student']);

          $role->givePermissionTo('access user');

          $role = Role::create(['name' => 'admin']);
          $role->givePermissionTo(Permission::all());
    }
}
