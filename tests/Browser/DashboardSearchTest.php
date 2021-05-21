<?php

namespace Tests\Browser;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DashboardSearchTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testRedirectedToDashboard()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::find(3))
                ->visit('/')
                ->assertRouteIs('dashboard');
        });
    }

    public function testServiceCount()
    {
        $this->browse(function (Browser $browser) {
            $services = Service::whereIsEnabled(true)->count();
            $browser
                ->loginAs(User::find(3))
                ->visit('/tools')
                ->assertSee($services . ' tools');
        });
    }

    public function testSearchFocus()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::find(3))
                ->visit('/tools')
                ->assertFocused('[type="search"]');
        });
    }

    public function testLoading()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::find(3))
                ->visit('/tools')
                ->type('[type="search"]', 'product')
                ->waitForText('Loading', 1)
                ->assertSee('Loading');
        });
    }

    public function testValidSearchLinked()
    {
        $this->browse(function (Browser $browser) {
            $service = Service::whereIsEnabled(true)->first();
            $name = $service->name;
            $browser
                ->loginAs(User::find(3))
                ->visit('/tools')
                ->type('[type="search"]', substr($name, 0, 6))
                ->waitForText($name, 5)
                ->assertSee($name)
                ->click('[href="' . route('tool', ['service' => $service->slug]) . '"]')
                ->assertRouteIs('tool', ['service' => $service->slug]);
        });
    }
}
