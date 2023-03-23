<?php

namespace Modules\AdminManagement\Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Modules\AdminManagement\Repositories\PermissionRepository;
use PHPUnit\Event\Code\Test;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PermissionControllerTest extends TestCase
{
    const PERMISSION = [
        'DELETE' => 'delete permission',
        'TEST'  => 'test permission'
    ];
    use  WithFaker, DatabaseTransactions;

    /**
     * Test Permission List Page With Valid Permission
     */
    public function testPermissionListPageWithValidPermission()
    {
        $this->createUserWithPermission('access permission', true);

        $response = $this->get(route('admin.permissions.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('adminmanagement::permissions.index');
    }

    /**
     * Test Permission List Page With Invalid Permission
     */
    public function testPermissionListPageWithInvalidPermission()
    {
        $this->createUserWithPermission('access permission', false);

        $response = $this->get(route('admin.permissions.index'));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * Test Store a newly created permission in database
     */
    public function testStoreNewlyCreatedPermissionDatabase()
    {
        $this->createUserWithPermission('create permission', true);

        $permission = $this->faker->word;

        $response = $this->post(route('admin.permissions.store'), [
            'name' => $permission,
        ]);

        $response->assertRedirect(route('admin.permissions.index'));

        $response->assertSessionHas('success', __('adminmanagement::auth.permission.created'));

        $this->assertDatabaseHas('permissions', [
            'name' => $permission,
        ]);
    }

    /**
     * Test Store a newly created permission in database
     */
    public function testStoreNewlyCreatedPermissionDatabaseWithInvalidPermission()
    {
        $this->createUserWithPermission('create permission', false);

        $permission = $this->faker->word;

        $response = $this->post(route('admin.permissions.store'), [
            'name' => $permission,
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * Test update permission in database
     */
    public function testUpdatePermissionDatabaseWithValidPermission()
    {
        $name = 'updates permission';

        $this->createUserWithPermission('edit permission', true);
        $permission = new PermissionRepository();

        $test = $permission->create((object) [
            'name' => self::PERMISSION['TEST'],
        ]);

        $response = $this->patch(route('admin.permissions.update', ['permission' => $test->id]), [
                'name' => $name]);

        $response->assertRedirect(route('admin.permissions.index'));

        $response->assertSessionHas('success', __('adminmanagement::auth.permission.updated'));

        $this->assertDatabaseHas('permissions', [
            'id'   => $test->id,
            'name' => $name
        ]);
    }

    /**
     * Test update permission in database
     */
    public function testUpdatePermissionDatabaseWithInvalidPermission()
    {
        $name = 'update permission';

        $this->createUserWithPermission('edit permission', false);

        //create test permission
        $test =  $this->createPermission('update permission');


        $response = $this->patch(route('admin.permissions.update', ['permission' => $test->id]), [
                'name' => $name]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }


    /**
     * Remove the permission resource from database
     */
    public function testRemovePermissionWithValidPermission()
    {
        $this->createUserWithPermission(self::PERMISSION['DELETE'], true);


        $test =  $this->createPermission(self::PERMISSION['TEST']);

        $this->assertDatabaseHas('permissions', [
            'id'   => $test->id,
            'name' => $test->name
        ]);

        $response = $this->delete(route('admin.permissions.destroy', ['permission' => $test->id]), [
            'page' => 1]);

        $response->assertRedirect(route('admin.permissions.index', ['page'=> 1]));

        $response->assertSessionHas('success', __('adminmanagement::auth.permission.deleted'));

    }


     /**
     *  unauthenticated user want to remove permission
     */
    public function testUnauthenticatedUserRemovePermission()
    {
        $this->createUserWithPermission(self::PERMISSION['DELETE'], false);

          //create test permission
        $test =  $this->createPermission(self::PERMISSION['TEST']);

        $this->assertDatabaseHas('permissions', [
            'id'   => $test->id,
            'name' => $test->name
        ]);

        $response = $this->delete(route('admin.permissions.destroy', ['permission' => $test->id]), [
            'page' => 1]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);

    }

    /**
     * Remove the permission resource from database With Invalid Id
     */
    public function testRemovePermissionWithInvalidData()
    {
        $this->createUserWithPermission(self::PERMISSION['DELETE'], true);

        $response = $this->delete(route('admin.permissions.destroy', ['permission' => 999]), [
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
}
