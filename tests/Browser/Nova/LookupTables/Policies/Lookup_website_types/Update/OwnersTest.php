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

namespace Tests\Browser\Nova\LookupTables\Policies\Lookup_website_types\Update;


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
     * @group novaLookuptablesPoliciesLookupwebsitetypes
     * @group novaLookuptablesPoliciesLookupwebsitetypesUpdate
     * @group novaLookuptablesPoliciesLookupwebsitetypesUpdateOwners
     */
    public function testOwners()
    {
        echo "\n**Now testing Tests\Browser\Nova\LookupTables\Policies\Lookupwebsitetypes\Update\TestOwners**";

        $this->insertGenericLookupRecord('lookup_website_types');

        $login = $this->loginOwnerBobBloom;
        $pause = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])

                ->assertSee('Lookup Website Types')
                ->clickLink('Lookup Website Types')
                ->pause($pause['long'])
                ->assertSee('Create Lookup Website Type')

                // The first 5 records are not edit-able!
                ->assertMissing('@1-edit-button')
                ->assertMissing('@2-edit-button')
                ->assertMissing('@3-edit-button')
                ->assertMissing('@4-edit-button')
                ->assertMissing('@5-edit-button')
                ->assertMissing('@6-edit-button')

                ->assertVisible('@7-edit-button')
            ;
        });
    }
}
