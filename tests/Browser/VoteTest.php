<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class VoteTest extends DuskTestCase
{
    const JAM_URL = "https://siliconbleach.com/jam";

    public function testExample() {
        print_r('Test is running');
        $this->browse(function (Browser $browser) {
            print_r('Test is browsing');
            $browser->visit(self::JAM_URL)
                ->click('.voting-button')
                ->screenshot('selectedButtons')
                ->click('#submitvotes-button')
                ->screenshot('clicked')
                ->type('#login-username', 'drunkteemoorgy')
                ->type('#login-password', 'Address123')
                ->screenshot('credentials')
                ->click('[data-a-target=passport-login-button]')
                ->screenshot('loggedIn');

        });
    }
}
