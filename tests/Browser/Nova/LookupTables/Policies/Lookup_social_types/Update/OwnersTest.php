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

namespace Tests\Browser\Nova\LookupTables\Policies\Lookup_social_types\Update;


// LaSalle Software
use Tests\Browser\Nova\LookupTables\LookupTablesBaseDuskTestCase;
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class OwnersTest extends LookupTablesBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test that the an owner can update.
     *
     * @group nova
     * @group novalookuptables
     * @group novaLookuptablesPolicies
     * @group novaLookuptablesPoliciesLookupsocialtypes
     * @group novaLookuptablesPoliciesLookupsocialtypesUpdate
     * @group novaLookuptablesPoliciesLookupsocialtypesUpdateOwners
     */
    public function testOwners()
    {
        echo "\n**Now testing Tests\Browser\Nova\LookupTables\Policies\Lookupsocialtypes\Update\TestOwners**";

        $this->insertGenericLookupRecord('lookup_social_types');

        $login = $this->loginOwnerBobBloom;
        $pause = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])

                ->assertSee('Lookup Social Types')
                ->clickLink('Lookup Social Types')
                ->pause($pause['long'])
                ->assertSee('Create Lookup Social Type')

                // The first 5 records are not edit-able!
                ->assertMissing('@1-edit-button')
                ->assertMissing('@2-edit-button')
                ->assertMissing('@3-edit-button')
                ->assertMissing('@4-edit-button')
                ->assertMissing('@5-edit-button')
                ->assertMissing('@6-edit-button')
                ->assertMissing('@7-edit-button')
                ->assertMissing('@8-edit-button')
                ->assertMissing('@9-edit-button')
                ->assertMissing('@10-edit-button')
                ->assertMissing('@11-edit-button')
                ->assertMissing('@12-edit-button')

                ->assertVisible('@13-edit-button')
            ;
        });
    }
}
