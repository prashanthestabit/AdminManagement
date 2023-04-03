<?php

namespace Modules\AdminManagement\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
        Permission::updateOrCreate(['name' => 'edit user'], ['name' => 'edit user']);
        Permission::updateOrCreate(['name' => 'delete user'], ['name' => 'delete user']);
        Permission::updateOrCreate(['name' => 'create user'], ['name' => 'create user']);
        Permission::updateOrCreate(['name' => 'access user'], ['name' => 'access user']);
        Permission::updateOrCreate(['name' => 'access role'], ['name' => 'access role']);
        Permission::updateOrCreate(['name' => 'create role'],['name' => 'create role']);
        Permission::updateOrCreate(['name' => 'edit role'],['name' => 'edit role']);
        Permission::updateOrCreate(['name' => 'delete role'], ['name' => 'delete role']);
        Permission::updateOrCreate(['name' => 'access permission'], ['name' => 'access permission']);
        Permission::updateOrCreate(['name' => 'create permission'], ['name' => 'create permission']);
        Permission::updateOrCreate(['name' => 'edit permission'], ['name' => 'edit permission']);
        Permission::updateOrCreate(['name' => 'delete permission'], ['name' => 'delete permission']);

        // create roles and assign created permissions

        //this can be done as separate statements
        $role = Role::updateOrCreate(['name' => 'student'], ['name' => 'student']);

        $role->givePermissionTo('access user');

        $role = Role::updateOrCreate(['name' => 'admin'], ['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        Role::findByName('admin')->users()->sync(User::pluck('id'));
    }
}
