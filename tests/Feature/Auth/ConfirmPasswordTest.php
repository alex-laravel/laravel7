<?php

namespace Tests\Feature\Auth;

use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ConfirmPasswordTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE_AUTH_PASSWORD_CONFIRM = '/auth/password/confirm';
    const ROUTE_AUTH_LOGIN = '/auth/login';
    const USER_PASSWORD_ORIGINAL = 'secret-password';

    /**
     * @return void
     */
    public function testConfirmPasswordRoute()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(self::ROUTE_AUTH_PASSWORD_CONFIRM);

        $response->assertStatus(200);
        $response->assertViewIs('auth.passwords.confirm');

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @return void
     */
    public function testConfirmPasswordRouteIfNotAuthenticated()
    {
        $response = $this->get(self::ROUTE_AUTH_PASSWORD_CONFIRM);

        $response->assertStatus(302);
        $response->assertRedirect(self::ROUTE_AUTH_LOGIN);
        $response->assertSessionHasNoErrors();

        $this->assertGuest();
    }

    /**
     * @return void
     */
    public function testConfirmPassword()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post(self::ROUTE_AUTH_PASSWORD_CONFIRM, [
            'password' => self::USER_PASSWORD_ORIGINAL,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertSessionHasNoErrors();

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @return void
     */
    public function testConfirmPasswordWithEmptyPassword()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from(self::ROUTE_AUTH_PASSWORD_CONFIRM)->post(self::ROUTE_AUTH_PASSWORD_CONFIRM, [
            'password' => '',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(self::ROUTE_AUTH_PASSWORD_CONFIRM);
        $response->assertSessionHasErrors('password');

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @return void
     */
    public function testConfirmPasswordWithIncorrectPassword()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from(self::ROUTE_AUTH_PASSWORD_CONFIRM)->post(self::ROUTE_AUTH_PASSWORD_CONFIRM, [
            'password' => 'incorrect-password',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(self::ROUTE_AUTH_PASSWORD_CONFIRM);
        $response->assertSessionHasErrors('password');

        $this->assertAuthenticatedAs($user);
    }
}
