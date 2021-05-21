<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testLoginRedirect()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertRouteIs('login');
        });
    }

    public function testInvalidLogin()
    {
        $this->browse(function (Browser $browser) {
            $user = User::where('email', 'LIKE', 'test@test.com')->firstOrFail();
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'invalidpassword')
                ->press('#login-button')
                ->assertRouteIs('login');
        });
    }

    public function testLogin()
    {
        $this->browse(function (Browser $browser) {
            $user = User::where('email', 'LIKE', 'test@test.com')->firstOrFail();
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'test')
                ->press('#login-button')
                ->assertRouteIs('dashboard');
        });
    }

    public function testLogout()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('logout'))
                ->assertRouteIs('login');
        });
    }
}
