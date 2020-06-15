<?php

namespace Tests\Feature\Backend;

use Tests\TestCase;

class DashboardPageTest extends TestCase
{
    /**
     * @return void
     */
    public function testPageDashboard()
    {
        $response = $this->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Backend Page');
    }
}
