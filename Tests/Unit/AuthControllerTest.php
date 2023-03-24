<?php

namespace Modules\AdminManagement\Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\AdminManagement\Repositories\AuthRepository;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use  WithFaker, DatabaseTransactions;

    /**
     * Test the postLogin method with valid credentials.
     *
     * @return void
     */
    public function testPostLoginWithValidCredentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $response = $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $response->assertSessionHas('success', __('adminmanagement::auth.login_success'));
    }

    /**
     * Test the postLogin method with invalid credentials.
     *
     * @return void
     */
    public function testPostLoginWithInvalidCredentials()
    {
        $credentials = [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->post(route('login.post'), $credentials);

        $response->assertRedirect(route('login'));

        $this->assertEquals(__('adminmanagement::auth.login_failed'), session('error'));
    }

    /**
     * Test the user register method with valid credentials.
     *
     * @return void
     */
    public function testSuccessfulRegistrationWithValidCredentials()
    {
        $userData = [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => 1,
        ];

        $response = $this->post(route('register.post'), $userData);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
        ]);

        $this->assertAuthenticated();
    }

    /**
     * Test the user register method with existing email.
     *
     * @return void
     */
    public function testRegistrationWithExistingEmail()
    {
        $existingUser = User::factory()->create();

        $userData = [
            'name' => $this->faker->name(),
            'email' => $existingUser->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => 1,
        ];

        $response = $this->post(route('register.post'), $userData);

        $response->assertSessionHasErrors('email');
    }

    /**
     * Test submit forget password form with valid email.
     *
     * @return void
     */
    public function testSubmitForgetPasswordFormWithValidEmail()
    {
        Mail::fake();

        $email = $this->faker->unique()->safeEmail;
        $user = User::factory()->create(['email' => $email]);

        $response = $this->post(route('forget.password.post'), [
            'email' => $user->email,
        ]);

        $response->assertRedirect(route('login'));

        $response->assertSessionHas('message', __('adminmanagement::auth.mailed_message'));

        Mail::assertNothingSent();

        //check token exist in database
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $email,
        ]);

        $authRepository = new AuthRepository();
        $authRepository->deletePasswordReset(['email' => $email]);
    }

    /**
     * Test submit forget password form with Invalid email.
     *
     * @return void
     */
    public function testSubmitForgetPasswordFormWithInvalidEmail()
    {
        $response = $this->post(route('forget.password.post'), [
            'email' => 'invalid-email',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * Test Submit Reset Password Form With Invalid Token
     */
    public function testSubmitResetPasswordFormWithValidToken()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);
        $token = Str::random(64);

        (new AuthRepository())->savePasswordReset($user->email, $token);

        $response = $this->post(route('reset.password.post'), [
            'email' => $user->email,
            'token' => $token,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertRedirect(route('login'))
            ->assertSessionHas('message', 'Your password has been changed!');
    }

    /**
     * Test Submit Reset Password Form With Invalid Token
     */
    public function testSubmitResetPasswordFormWithInvalidToken()
    {
        $user = User::factory()->create();
        $invalidToken = 'invalid_token';

        $response = $this->post(route('reset.password.post'), [
            'email' => $user->email,
            'token' => $invalidToken,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertSessionHas('error', __('adminmanagement::auth.invalid_token'));
    }

    /**
     * Check User can Logout
     */
    public function testUserCanLogout()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        //Set the currently logged in user for the application
        $this->actingAs($user);

        $response = $this->get(route('admin.logout'));

        $response->assertRedirect(route('login'));

        $this->assertGuest();
        $this->assertNull(Auth::user());
    }
}
