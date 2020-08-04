<?php

namespace Tests\Feature\Auth;

use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE_AUTH_REGISTER = '/auth/register';
    const USER_PASSWORD_ORIGINAL = 'secret-password';

    /**
     * @return void
     */
    public function testRegisterRoute()
    {
        $response = $this->get(self::ROUTE_AUTH_REGISTER);

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    /**
     * @return void
     */
    public function testRegisterRouteIfAuthenticated()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(self::ROUTE_AUTH_REGISTER);

        $response->assertStatus(302);
        $response->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @return void
     */
    public function testUserRegister()
    {
        $user = factory(User::class)->make();

        $response = $this->post(self::ROUTE_AUTH_REGISTER, [
            'name' => $user->name,
            'email' => $user->email,
            'password' => self::USER_PASSWORD_ORIGINAL,
            'password_confirmation' => self::USER_PASSWORD_ORIGINAL,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertSessionHasNoErrors();

        $this->assertAuthenticated();
    }

    /**
     * @return void
     */
    public function testUserRegisterWithExistingEmail()
    {
        $user = factory(User::class)->create();

        $response = $this->from(self::ROUTE_AUTH_REGISTER)->post(self::ROUTE_AUTH_REGISTER, [
            'name' => $user->name,
            'email' => $user->email,
            'password' => self::USER_PASSWORD_ORIGINAL,
            'password_confirmation' => self::USER_PASSWORD_ORIGINAL,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(self::ROUTE_AUTH_REGISTER);
        $response->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    /**
     * @return void
     */
    public function testUserRegisterWithEmptyEmail()
    {
        $user = factory(User::class)->make();

        $response = $this->from(self::ROUTE_AUTH_REGISTER)->post(self::ROUTE_AUTH_REGISTER, [
            'name' => $user->name,
            'email' => '',
            'password' => self::USER_PASSWORD_ORIGINAL,
            'password_confirmation' => self::USER_PASSWORD_ORIGINAL,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(self::ROUTE_AUTH_REGISTER);
        $response->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    /**
     * @return void
     */
    public function testUserRegisterWithUnconfirmedPassword()
    {
        $user = factory(User::class)->make();

        $response = $this->from(self::ROUTE_AUTH_REGISTER)->post(self::ROUTE_AUTH_REGISTER, [
            'name' => $user->name,
            'email' => $user->email,
            'password' => self::USER_PASSWORD_ORIGINAL,
            'password_confirmation' => 'incorrect-password',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(self::ROUTE_AUTH_REGISTER);
        $response->assertSessionHasErrors('password');

        $this->assertGuest();
    }

    /**
     * @return void
     */
    public function testUserRegisterWithEmptyName()
    {
        $user = factory(User::class)->make();

        $response = $this->from(self::ROUTE_AUTH_REGISTER)->post(self::ROUTE_AUTH_REGISTER, [
            'name' => '',
            'email' => $user->email,
            'password' => self::USER_PASSWORD_ORIGINAL,
            'password_confirmation' => self::USER_PASSWORD_ORIGINAL
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(self::ROUTE_AUTH_REGISTER);
        $response->assertSessionHasErrors('name');

        $this->assertGuest();
    }
}
