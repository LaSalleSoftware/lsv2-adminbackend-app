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

namespace Tests\Browser\Nova\PersonbydomainsTable\Policies\Lookup_roles\Attach;

// LaSalle Software
use Tests\Browser\Nova\PersonbydomainsTable\PersonbydomainsTableBaseDuskTest;
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

// Laravel facade
use Illuminate\Support\Facades\DB;

class AttachAnyWhenNoRolesTest extends PersonbydomainsTableBaseDuskTest
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * When there are no roles assigned to the personbydomain (that is, no record in the personbydomain_lookup_roles
     * pivot table), the "Attach Lookup User Role" should display.
     *
     * @group nova
     * @group novaPersonbydomain
     * @group novaPersonbydomainPolicies
     * @group novaPersonbydomainPoliciesLookuproles
     * @group novaPersonbydomainPoliciesLookuprolesAttach
     * @group novaPersonbydomainPoliciesLookuprolesAttachAttachanywhennorole
     */
    public function testAttachAnyWhenNoRoles()
    {
        echo "\n**Now testing Tests\Browser\Nova\PersonbydomainsTable\Policies\Lookup_roles\Attach\TestAttachAnyWhenNoRoles**";

        // Arrange
        // want to see the "Attach" button, so need to remove a record from the personbydomain_lookup_roles pivot db table
        DB::table('personbydomain_lookup_roles')->where('id', 2)->delete();

        $personTryingToLogin  = $this->loginOwnerBobBloom;
        $pause                = $this->pause();

        // Act and Assert
        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->assertSee('Personbydomains')
                ->clickLink('Personbydomain')
                ->pause($pause['long'])
                ->pause($pause['long'])
                ->assertVisible('@2-view-button')
                ->click('@2-view-button')
                ->pause($pause['long'])
                ->assertSee('Personbydomain Details')
                ->assertSee('Lookup User Role')
                ->assertMissing('@2-row')
                ->assertSee('Attach Lookup User Role')
            ;
        });
    }
}
