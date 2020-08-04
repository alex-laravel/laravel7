<?php

namespace Tests\Feature\Auth;

use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE_AUTH_LOGIN = '/auth/login';
    const ROUTE_AUTH_LOGOUT = '/auth/logout';
    const USER_PASSWORD_ORIGINAL = 'secret-password';

    /**
     * @return void
     */
    public function testLoginRoute()
    {
        $response = $this->get(self::ROUTE_AUTH_LOGIN);

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /**
     * @return void
     */
    public function testLoginRouteIfAuthenticated()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(self::ROUTE_AUTH_LOGIN);

        $response->assertStatus(302);
        $response->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @return void
     */
    public function testUserLogin()
    {
        $user = factory(User::class)->create();

        $response = $this->post(self::ROUTE_AUTH_LOGIN, [
            'email' => $user->email,
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
    public function testUserLoginWithEmptyEmail()
    {
        factory(User::class)->create();

        $response = $this->from(self::ROUTE_AUTH_LOGIN)->post(self::ROUTE_AUTH_LOGIN, [
            'email' => '',
            'password' => self::USER_PASSWORD_ORIGINAL,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(self::ROUTE_AUTH_LOGIN);
        $response->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    /**
     * @return void
     */
    public function testUserLoginWithEmptyPassword()
    {
        $user = factory(User::class)->create();

        $response = $this->from(self::ROUTE_AUTH_LOGIN)->post(self::ROUTE_AUTH_LOGIN, [
            'email' => $user->email,
            'password' => '',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(self::ROUTE_AUTH_LOGIN);
        $response->assertSessionHasErrors('password');

        $this->assertGuest();
    }

    /**
     * @return void
     */
    public function testUserLoginWithIncorrectPassword()
    {
        $user = factory(User::class)->create();

        $response = $this->from(self::ROUTE_AUTH_LOGIN)->post(self::ROUTE_AUTH_LOGIN, [
            'email' => $user->email,
            'password' => 'incorrect-password',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(self::ROUTE_AUTH_LOGIN);
        $response->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    /**
     * @return void
     */
    public function testUserLogout()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post(self::ROUTE_AUTH_LOGOUT);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertSessionHasNoErrors();

        $this->assertGuest();
    }

    /**
     * @return void
     */
    public function testUserLogoutIfNotAuthenticated()
    {
        $user = factory(User::class)->create();

        $response = $this->post(self::ROUTE_AUTH_LOGOUT, [
            'email' => $user->email,
            'password' => self::USER_PASSWORD_ORIGINAL,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(self::ROUTE_AUTH_LOGIN);

        $this->assertGuest();
    }

    /**
     * @return void
     */
    public function testUserLoginRememberMe()
    {
        $user = factory(User::class)->create();

        $response = $this->post(self::ROUTE_AUTH_LOGIN, [
            'email' => $user->email,
            'password' => self::USER_PASSWORD_ORIGINAL,
            'remember' => 'on',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertSessionHasNoErrors();

        $this->assertAuthenticatedAs($user);

        $response->assertCookie(Auth::guard()->getRecallerName(), vsprintf('%s|%s|%s', [
            $user->id,
            $user->getRememberToken(),
            $user->password,
        ]));
    }
}
