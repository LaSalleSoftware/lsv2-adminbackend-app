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

/**
 * BTW, There is no deletion test for Admins & Super Admin because the index listing where the delete button displays
 * is already tested in the "Index" tests. Since the index listing is not available, the delete button is not available.
 */

namespace Tests\Browser\Nova\LookupTables\Policies\Lookup_website_types\Delete;


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
     * Test that the an owner can delete.
     *
     * @group nova
     * @group novalookuptables
     * @group novaLookuptablesPolicies
     * @group novaLookuptablesPoliciesLookupwebsitetypes
     * @group novaLookuptablesPoliciesLookupwebsitetypesDelete
     * @group novaLookuptablesPoliciesLookupwebsitetypesDeleteOwners
     */
    public function testOwners()
    {
        echo "\n**Now testing Tests\Browser\Nova\LookupTables\Policies\Lookupwebsitetypes\Delete\TestOwners**";

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

                // The first 5 records are not delete-able!
                ->assertMissing('@1-delete-button')
                ->assertMissing('@2-delete-button')
                ->assertMissing('@3-delete-button')
                ->assertMissing('@4-delete-button')
                ->assertMissing('@5-delete-button')
                ->assertMissing('@6-delete-button')

                ->assertVisible('@7-delete-button')
            ;
        });
    }
}
