<?php

namespace Modules\AdminManagement\Tests\Unit;

use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Mockery;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserControllerTest extends TestCase
{
    use  WithFaker, DatabaseTransactions;

    const USER = [
        'ACCESS' => 'access user',
        'CREATE' => 'create user',
        'EDIT'   => 'edit user',
        'DELETE' => 'delete user',
    ];

    /**
     * Test user list with authorized user
     */
    public function testShouldReturnAListOfUsers()
    {
        $this->createUserWithPermission(self::USER['ACCESS'], true);

        user::factory(10)->create();

        $response = $this->get(route('admin.users.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('data');
        $response->assertViewIs('adminmanagement::users.index');
    }

    /**
     * Test get user list when user has no Access
     */
    public function testShouldReturn403ForbiddenIfUserHasNoAccess()
    {
        $this->createUserWithPermission(self::USER['ACCESS'], false);

        $response = $this->get(route('admin.users.index'));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * Test Store a newly created user in database
     */
    public function testStoreCreatedUserinDatabase()
    {
        // Given
        $this->createUserWithPermission(self::USER['CREATE'], true);


        $response = $this->post(route('admin.users.store'), [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => 'password',
            'password_confirmation' => 'password',
            'roles' => ['admin'],
            'permissions' => ['create role'],
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', __('adminmanagement::auth.user.created'));
    }


    /**
     * Test Store a newly created user with invalid data
     */
    public function testStoreCreatedUserWithInvalidData()
    {
        $this->createUserWithPermission(self::USER['CREATE'], true);


        $response = $this->post(route('admin.users.store'), [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => $this->faker->text(8),
            'password_confirmation' => $this->faker->text(8),
            'roles' => ['admin'],
            'permissions' => ['access role'],
        ]);
        $response->assertRedirect();
        $response->assertSessionHasErrors('password');
    }


    /**
     * Test Store a newly created user when user has no access
     */
    public function testStoreShouldReturn403ForbiddenIfUserHasNoAccess()
    {
        $this->createUserWithPermission(self::USER['CREATE'], false);

        $response = $this->post(route('admin.users.store'), [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => 'password',
            'password_confirmation' => 'password',
            'roles' => ['admin'],
            'permissions' => ['access role'],
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * Test Update a user in database
     */
    public function testUpdateUserinDatabase()
    {
        $this->createUserWithPermission(self::USER['EDIT'], true);

        $user = user::factory()->create([
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
        ]);

        $response =  $response = $this->patch(route('admin.users.update', ['user' => $user->id]), [
            'name' => 'Test Name',
            'email' => $user->email,
            'roles' => ['student'],
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', __('adminmanagement::auth.user.updated'));
    }


    /**
     * Test Update a user in with invalid data
     */
    public function testUpdateUserWithInvalidData()
    {
        $this->createUserWithPermission(self::USER['EDIT'], true);

        $user = user::factory()->create([
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
        ]);

        $response =  $response = $this->patch(route('admin.users.update', ['user' => 999]), [
            'name' => 'Test Name',
            'email' => $user->email,
            'roles' => ['student'],
        ]);

       $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);

    }

    /**
     * Remove the specified user from database.
     */
    public function testRemoveUserInDatabase()
    {
        $this->createUserWithPermission(self::USER['DELETE'], true);


        $user = user::factory()->create([
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id
        ]);

        $response = $this->delete(route('admin.users.destroy', ['user' => $user->id]), [
            'page' => 1]);

        $response->assertRedirect(route('admin.users.index', ['page'=> 1]));

        $response->assertSessionHas('success', __('adminmanagement::auth.user.deleted'));

        $this->assertDatabaseMissing('users',[
            'id' => $user->id
        ]);
    }

    /**
     * Remove the specified user from database with invalid id.
     */
    public function testRemoveUserWithInvalidId()
    {
        $this->createUserWithPermission(self::USER['DELETE'], true);

        $response = $this->delete(route('admin.users.destroy', ['user' => 999]), [
            'page' => 1]);

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);

    }




    private function createUserWithPermission($permission, $checkPermission)
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
}
