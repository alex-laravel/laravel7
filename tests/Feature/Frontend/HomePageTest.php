<?php

namespace Tests\Feature\Frontend;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    /**
     * @return void
     */
    public function testPageHome()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('frontend.index');
    }
}
