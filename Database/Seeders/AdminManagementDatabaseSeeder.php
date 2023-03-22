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
          Permission::create(['name' => 'access role']);
          Permission::create(['name' => 'create role']);
          Permission::create(['name' => 'edit role']);
          Permission::create(['name' => 'delete role']);
          Permission::create(['name' => 'access permission']);
          Permission::create(['name' => 'create permission']);
          Permission::create(['name' => 'edit permission']);
          Permission::create(['name' => 'delete permission']);


          // create roles and assign created permissions

          // this can be done as separate statements
          $role = Role::create(['name' => 'student']);

          $role->givePermissionTo('access user');

          $role = Role::create(['name' => 'admin']);
          $role->givePermissionTo(Permission::all());
    }
}
