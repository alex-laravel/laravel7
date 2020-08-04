<?php

namespace Tests\Feature\Locale;

use Tests\TestCase;

class LocaleTest extends TestCase
{
    /**
     * @return void
     */
    public function testSwapLocale()
    {
        $response = $this->get('/locale/en');

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertSessionHas('locale', 'en');
    }
}
