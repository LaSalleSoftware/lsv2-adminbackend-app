<?php

/**
 * This file is part of the Lasalle Software library (lasallesoftware/library)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * @link       https://lasallesoftware.ca \Lookup_address_type;log, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\PersonbydomainsTable\Forms\Update\Password;

// LaSalle Software classes
use Tests\Browser\Nova\PersonbydomainsTable\PersonbydomainsTableBaseDuskTest;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;

class LeavePasswordUnchangedIsSuccessfulTest extends PersonbydomainsTableBaseDuskTest
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        // Yes, I am using the blog seeds!
        $this->artisan('lslibrary:customseed');
        $this->artisan('lslibrary:installeddomainseed');
    }

    /**
     * Test that a personbydomain update with the password unchanged actually leaves the password unchanged.
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainForms
     * @group novaPersonbydomainFormsUpdate
     * @group novaPersonbydomainFormsUpdatePassword
     * @group novaPersonbydomainFormsUpdatePasswordLeavepasswordunchangedissuccessful
     */
    public function testLeavePasswordUnchangedIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\PersonbydomainsTable\Forms\Update\Password\LeavePasswordUnchangedIsSuccessfulTest**";

        $personTryingToLogin = $this->loginOwnerBobBloom;
        $pause               = $this->pause;

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->assertSee('Personbydomains')
                ->clickLink('Personbydomains')
                ->waitFor('@1-row')
                ->pause($pause['shortest'])
                ->assertVisible('@1-row')
                ->assertVisible('@1-edit-button')
                ->click('@1-edit-button')
                ->pause($pause['medium'])
                ->assertSee('Update Personbydomain')
                ->click('@update-button')
                ->pause($pause['medium'])
                ->assertSee('Personbydomain Details')
            ;
        });

        $password = DB::table('personbydomains')->where('id', 2)->pluck('password')->first();
        $this->assertEquals('$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', $password);
    }
}