<?php

namespace Tests\Feature\Auth;

use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    const ROUTE_AUTH_RESET_PASSWORD_TOKEN = '/auth/password/reset/token';
    const ROUTE_AUTH_RESET_PASSWORD_RESET = '/auth/password/reset';
    const USER_PASSWORD_ORIGINAL = 'secret-password';

    /**
     * @return void
     */
    public function testResetPasswordRoute()
    {
        $response = $this->get(self::ROUTE_AUTH_RESET_PASSWORD_TOKEN);

        $response->assertStatus(200);
        $response->assertViewIs('auth.passwords.reset');
    }

    /**
     * @return void
     */
    public function testResetPasswordRouteIfAuthenticated()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(self::ROUTE_AUTH_RESET_PASSWORD_TOKEN);

        $response->assertStatus(302);
        $response->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @return void
     */
    public function testResetPassword()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make(self::USER_PASSWORD_ORIGINAL)
        ]);

        $token = Password::broker()->createToken($user);

        $password = Str::random(8);

        $response = $this->post(self::ROUTE_AUTH_RESET_PASSWORD_RESET, [
            'token' => $token,
            'email' => $user->email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertSessionHasNoErrors();

        $user->refresh();

        $this->assertFalse(Hash::check(self::USER_PASSWORD_ORIGINAL, $user->password));
        $this->assertTrue(Hash::check($password, $user->password));
    }

    /**
     * @return void
     */
    public function testResetPasswordWithNotExistingEmail()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make(self::USER_PASSWORD_ORIGINAL)
        ]);

        $token = Password::broker()->createToken($user);

        $password = Str::random(8);

        $response = $this->post(self::ROUTE_AUTH_RESET_PASSWORD_RESET, [
            'token' => $token,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertSessionHasErrors('email');
    }

    /**
     * @return void
     */
    public function testResetPasswordWithInvalidEmail()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make(self::USER_PASSWORD_ORIGINAL)
        ]);

        $token = Password::broker()->createToken($user);

        $email = Str::random(16);
        $password = Str::random(8);

        $response = $this->post(self::ROUTE_AUTH_RESET_PASSWORD_RESET, [
            'token' => $token,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertSessionHasErrors('email');
    }

    /**
     * @return void
     */
    public function testResetPasswordWithUnconfirmedPassword()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make(self::USER_PASSWORD_ORIGINAL)
        ]);

        $token = Password::broker()->createToken($user);

        $response = $this->post(self::ROUTE_AUTH_RESET_PASSWORD_RESET, [
            'token' => $token,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Str::random(8),
            'password_confirmation' => Str::random(8),
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertSessionHasErrors('password');
    }

    /**
     * @return void
     */
    public function testResetPasswordWithInvalidPassword()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make(self::USER_PASSWORD_ORIGINAL)
        ]);

        $token = Password::broker()->createToken($user);

        $password = Str::random(5);

        $response = $this->post(self::ROUTE_AUTH_RESET_PASSWORD_RESET, [
            'token' => $token,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertSessionHasErrors('password');
    }
}
