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

namespace Tests\Browser\Nova\PersonbydomainsTable\Ban_User;

// LaSalle Software classes
use Tests\Browser\Nova\PersonbydomainsTable\PersonbydomainsTableBaseDuskTest;
use Lasallesoftware\Library\Authentication\Models\Personbydomain;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;

class EmergencyBanDisableTest extends PersonbydomainsTableBaseDuskTest
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');
    }

    /**
     * Test that a personbydomain can login when the emergency ban is disabled
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainBanuser
     * @group novaPersonbydomainBanuserEmergencyban
     * @group novaPersonbydomainBanuserEmergencybanIsdisabled
     */
    public function testIsDisabled()
    {
        echo "\n**Now testing Tests\Browser\Nova\PersonbydomainsTable\Ban_User\EmergencyBanDisableTest**";

        $personTryingToLogin = $this->loginOwnerBobBloom;
        $pause               = $this->pause();
        
        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Contact Form Submissions')  
                ->assertSee('Telephone Numbers')              
            ;
        });
    }
}
