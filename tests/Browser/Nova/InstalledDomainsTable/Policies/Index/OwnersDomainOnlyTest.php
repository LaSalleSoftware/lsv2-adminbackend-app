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
 * @link       https://lasallesoftware.ca \Lookup_address_type;log, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\InstalledDomainsTable\Policies\Index;


// LaSalle Software
use Tests\Browser\Nova\InstalledDomainsTable\InstalledDomainsTableBaseDuskTestCase;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class OwnersDomainOnlyTest extends InstalledDomainsTableBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lslibrary:installeddomainseed');

    }

    /**
     * Test that the owners see the index listing.
     *
     * Please note that the index listing is controlled by the resource's indexQuery() method!
     *
     * @group nova
     * @group NovaInstalleddomainstable
     * @group NovaInstalleddomainstablePolicies
     * @group NovaInstalleddomainstablePoliciesIndex
     * @group NovaInstalleddomainstablePoliciesIndexOwnersdomainonly
     */
    public function testOwnersDomainOnly()
    {
        echo "\n**Now testing Tests\Browser\Nova\InstalledDomainsTable\Policies\Index\TestOwnersDomainOnly**";

        $login = $this->loginOwnerBobBloom;
        $pause = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertPathIs('/nova/resources/personbydomains')
                ->assertSee('Personbydomains')
                ->assertSee('Installed Domains')
                ->clickLink('Installed Domains')
                ->pause($pause['long'])
                ->assertSee(env('LASALLE_APP_DOMAIN_NAME'))
                ->assertSee('Pretendfrontend.com')
                ->assertSee('Anotherpretendfrontend.com')
            ;
        });
    }
}
