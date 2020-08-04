<?php

namespace Tests\Feature\Backend;

use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DashboardPageTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE_AUTH_LOGIN = '/auth/login';
    const ROUTE_ADMIN_DASHBOARD = '/admin/dashboard';

    /**
     * @return void
     */
    public function testPageDashboard()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(self::ROUTE_ADMIN_DASHBOARD);

        $response->assertStatus(200);
        $response->assertViewIs('backend.index');

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @return void
     */
    public function testPageDashboardIfNotAuthenticated()
    {
        $response = $this->get(self::ROUTE_ADMIN_DASHBOARD);

        $response->assertStatus(302);
        $response->assertRedirect(self::ROUTE_AUTH_LOGIN);
        $response->assertSessionHasNoErrors();

        $this->assertGuest();
    }
}
