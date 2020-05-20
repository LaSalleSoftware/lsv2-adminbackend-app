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

namespace Tests\Browser\Nova\LookupTables\Policies\Lookup_roles\Personbydomain\EditAttach;


// LaSalle Software
use Tests\Browser\Nova\LookupTables\LookupTablesBaseDuskTestCase;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SuppressEditAttachedButtonTest extends LookupTablesBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Suppress the attach button.
     *
     * @group nova
     * @group novalookuptables
     * @group novaLookuptablesPolicies
     * @group novaLookuptablesPoliciesLookuproles
     * @group novaLookuptablesPoliciesLookuprolesPersonbydomain
     * @group novaLookuptablesPoliciesLookuprolesPersonbydomainUpdate
     * @group novaLookuptablesPoliciesLookuprolesPersonbydomainUpdateuppresseditattachedbutton
     */
    public function testuppressEditAttachedButton()
    {
        echo "\n**Now testing Tests\Browser\Nova\LookupTables\Policies\Lookuproles\Personbydomain\EditAttach\TestuppressEditAttachedButton**";

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
                ->assertSee('Lookup User Roles')

                ->clickLink('Lookup User Roles')
                ->pause($pause['long'])
                ->assertSee('Create Lookup User Role')
                ->assertVisible('@2-view-button')

                ->click('@2-view-button')
                ->pause($pause['short'])
                ->assertSee('Lookup User Role Details')
                ->assertSee('Personbydomains')

                ->pause($pause['long'])

                ->assertVisible('@4-row')
                ->assertMissing('@4-edit-attached-button')
            ;
        });
    }
}
