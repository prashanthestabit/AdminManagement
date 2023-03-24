<?php

namespace Modules\AdminManagement\Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Modules\AdminManagement\Repositories\PermissionRepository;
use Modules\AdminManagement\Repositories\RoleRepository;
use PHPUnit\Event\Code\Test;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Tests\TestCase;

class RoleControllerTest extends TestCase
{
    use  WithFaker, DatabaseTransactions;

    const ROLE = [
        'ACCESS' => 'access role',
        'CREATE' => 'create role',
        'EDIT'   => 'edit role',
        'DELETE' => 'delete role',
    ];

    const PERMISSION = [
        'DELETE' => 'delete permission',
        'TEST'  => 'test permission'
    ];

    const FORBIDDEN = '403 Forbidden';

    const PERPAGE = 5;

    const ERROR = 'adminmanagement::auth.error';

    use  WithFaker, DatabaseTransactions;

    /**
     * Test Role List Page With Valid Permission
     */
    public function testRoleListPageWithValidPermission()
    {

        $this->createUserWithPermission(self::ROLE['ACCESS'], true);

        $response = $this->get(route('admin.roles.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('adminmanagement::roles.index');
    }

    /**
     * Test Role List Page With Invalid Permission
     */
    public function testRoleListPageWithInvalidPermission()
    {
        $this->createUserWithPermission(self::ROLE['ACCESS'], false);

        $response = $this->get(route('admin.roles.index'));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * Test Store a newly created role in database
     */
    public function testStoreNewlyCreatedRoleInDatabase()
    {
        $permission = new PermissionRepository();

        $this->createUserWithPermission(self::ROLE['CREATE'], true);

        $roleName = $this->faker->word;

        $test = $permission->create((object) [
            'name' => self::PERMISSION['TEST'],
        ]);

        $permissions[] = $test->id;

        $response = $this->post(route('admin.roles.store'), [
            'name' => $roleName,
            'permissions' => $permissions
        ]);

        $response->assertRedirect(route('admin.roles.index'));

        $response->assertSessionHas('success', __('adminmanagement::auth.role.created'));

        $this->assertDatabaseHas('roles', [
            'name' => $roleName,
        ]);
    }

    /**
     * Test Store a newly created role in database With Invalid Data
     */
    public function testStoreNewlyCreatedRoleInDatabaseWithInvalidData()
    {

        $this->createUserWithPermission(self::ROLE['CREATE'], true);

        $roleName = $this->faker->word;

        $permissions[] = 999;

        $response = $this->post(route('admin.roles.store'), [
            'name' => $roleName,
            'permissions' => $permissions
        ]);
        $response->assertSessionHasErrors('permissions');
    }

    /**
     * Test Store a newly created role with InvalidPermission
     */
    public function testStoreNewlyCreatedRoleDatabaseWithInvalidPermission()
    {
        $permission = new PermissionRepository();

        $this->createUserWithPermission(self::ROLE['CREATE'], false);

        $test = $permission->create((object) [
            'name' => self::PERMISSION['TEST'],
        ]);

        $permissions[] = $test->id;

        $response = $this->post(route('admin.roles.store'), [
            'name' => $this->faker->word,
            'permissions' => $permissions
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * Test update role in database
     */
    public function testUpdateRoleDatabaseWithValidPermission()
    {
        $name = 'updates role';

        $this->createUserWithPermission(self::ROLE['EDIT'], true);

        $test = $this->createPermission(self::PERMISSION['TEST']);

        $permissions[] = $test->id;

        $roleData = $this->createRole($permissions);

        $response = $this->patch(route('admin.roles.update', ['role' => $roleData->id]), [
                'name' => $name,
                'permissions' => $permissions
            ]);

        $response->assertRedirect(route('admin.roles.index'));

        $response->assertSessionHas('success', __('adminmanagement::auth.role.updated'));

        $this->assertDatabaseHas('roles', [
            'id'   => $roleData->id,
            'name' => $name
        ]);
    }

    /**
     * Test Update a created role in database With Invalid Data
     */
    public function testUpdateNewlyCreatedRoleInDatabaseWithInvalidData()
    {

        $this->createUserWithPermission(self::ROLE['EDIT'], true);

        $test = $this->createPermission(self::PERMISSION['TEST']);

        $permissions[] = $test->id;

        $roleData = $this->createRole($permissions);

        $wrongPermissions[] = 999;

        $response = $this->patch(route('admin.roles.update', ['role' => $roleData->id]), [
            'name' => $this->faker->word,
            'permissions' => $wrongPermissions
        ]);
        $response->assertSessionHasErrors('permissions');
    }

    /**
     * Test update role in database With Invalid Permission
     */
    public function testUpdateRoleDatabaseWithInvalidPermission()
    {
        $name = 'update role';

        $this->createUserWithPermission(self::ROLE['EDIT'], false);


        $test = $this->createPermission(self::PERMISSION['TEST']);

        $permissions[] = $test->id;

        $roleData = $this->createRole($permissions);

        $response = $this->patch(route('admin.roles.update', ['role' => $roleData->id]), [
                'name' => $name,
                'permissions' => $permissions
            ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }


    /**
     * Remove the role resource from database
     */
    public function testRemoveRoleWithValidPermission()
    {
        $this->createUserWithPermission(self::ROLE['DELETE'], true);

        $test = $this->createPermission(self::PERMISSION['TEST']);

        $permissions[] = $test->id;

        $roleData = $this->createRole($permissions);

        $this->assertDatabaseHas('roles', [
            'id'   => $roleData->id,
            'name' => $roleData->name
        ]);

        $response = $this->delete(route('admin.roles.destroy', ['role' => $roleData->id]), [
            'page' => 1]);

        $response->assertRedirect(route('admin.roles.index', ['page'=> 1]));

        $response->assertSessionHas('success', __('adminmanagement::auth.role.deleted'));

    }


     /**
     *  unauthenticated user want to remove role
     */
    public function testUnauthenticatedUserRemoveRole()
    {
        $this->createUserWithPermission(self::ROLE['DELETE'], false);

        $test = $this->createPermission(self::PERMISSION['TEST']);

        $permissions[] = $test->id;

        $roleData = $this->createRole($permissions);

        $this->assertDatabaseHas('roles', [
            'id'   => $roleData->id,
            'name' => $roleData->name
        ]);

        $response = $this->delete(route('admin.roles.destroy', ['role' => $roleData->id]), [
            'page' => 1]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);

    }

    /**
     * Remove the role resource from database With Invalid Id
     */
    public function testRemoveRoleWithInvalidData()
    {
        $this->createUserWithPermission(self::ROLE['DELETE'], true);

        $response = $this->delete(route('admin.roles.destroy', ['role' => 999]), [
            'page' => 1]);

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    protected function createUserWithPermission($permission, $checkPermission)
    {
        $user = User::factory()->create([
            'email' => $this->faker->safeEmail,
            'password' => Hash::make('password'),
        ]);

        if ($checkPermission) {
            $user->givePermissionTo($permission);
        } else {
            $user->revokePermissionTo($permission);
        }

        $this->actingAs($user);

        return $user;
    }



    protected function createPermission($permissionName)
    {
        $permission = new PermissionRepository();

        return $permission->create((object) [
            'name' => $permissionName,
        ]);
    }

    protected function createRole($permissions)
    {
        $role = new RoleRepository();

        return $role->create((object)[
            'name' => $this->faker->word,
            'permissions' => $permissions
           ]);
    }
}
