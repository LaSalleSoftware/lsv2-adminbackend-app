<?php

/**
 * This file is part of  Lasalle Software 
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * @link       https://lasallesoftware.ca
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\PersonbydomainsTable\Policies\Update;

// LaSalle Software
use Tests\Browser\Nova\PersonbydomainsTable\PersonbydomainsTableBaseDuskTest;
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminsTest extends PersonbydomainsTableBaseDuskTest
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test that admins can update their own personbydomain record.
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainPolicies
     * @group novaPersonbydomainPoliciesUpdate
     * @group novaPersonbydomainPoliciesUpdateAdmins
     */
    public function testAdmins()
    {
        echo "\n**Now testing Tests\Browser\Nova\PersonbydomainsTable\Policies\Update\TestAdmins**";

        $personTryingToLogin  = $this->loginAdminDomain1;
        $pause                = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->visit('/nova/resources/personbydomains')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->assertDontSee('Lookup User Roles')  // just an added assert that this menu item is not visible in the sidebar
                ->pause($pause['long'])
                ->assertVisible('@5-edit-button')
                ->assertMissing('@1-edit-button')  // bob.bloom@lasallesoftware.ca, in the test seeding (actually, in the initial seeding)
                ->assertMissing('@2-edit-button')  // bbking@kingofblues.com, in the test seeding
                ->assertMissing('@3-edit-button')  // srv@doubletrouble.com, in the test seeding
                ->assertMissing('@4-edit-button')  // sidney.bechet@blogtest.ca, in the test seeding
            ;
        });
    }
}
