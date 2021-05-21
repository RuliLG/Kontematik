<?php

namespace Tests\Browser;

use App\Models\Service;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DashboardTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testTools()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Email');
        });
    }
}
