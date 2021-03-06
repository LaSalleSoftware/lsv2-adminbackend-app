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

namespace Tests\Browser\Nova\LookupTables;

// LaSalle Software classes
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;
use Lasallesoftware\Librarybackend\Profiles\Models\Lookup_address_type;
use Tests\LaSalleDuskTestCase;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreationLookupTableTest extends LaSalleDuskTestCase
{
    use DatabaseMigrations;

    protected $personTryingToLogin;
    protected $newLookupTableData;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');

        $this->personTryingToLogin = [
            'email'    => 'bob.bloom@lasallesoftware.ca',
            'password' => 'secret',
        ];

        $this->newLookupTableData = [
            'title'              => 'Blues Boy King',
            'description'        => 'Lucille',
            'toolongdescription' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut enim metus, tristique pulvinar cursus in, pretium condimentum erat. Sed commodo  hendrerit nisi, eu faucibus felis molestie eget. Donec nec nisl mauris. Suspendisse potenti. Nunc massa est, egestas vitae hendrerit at, vestibulum in justo. Proin luctus ex vitae nisl ultrices, id semper eros interdum. In scelerisque nisl id tortor consequat placerat.',
        ];
    }

    /**
     * Test that the lookup table record creation is successful
     *
     * @group nova
     * @group novalookuptables
     * @group novaLookuptablesCreationlookuptable
     */
    public function testInsertNewRecordToLookupTableSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\LookupTables\CreationLookupTableTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $newLookupTableData  = $this->newLookupTableData;
        $pause               = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $newLookupTableData, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])

                ->clickLink('Lookup Address Types')
                ->pause($pause['long'])
                ->assertSee('Create Lookup Address Type')
                ->clickLink('Create Lookup Address Type')
                ->pause($pause['long'])
                ->assertSee('Create Lookup Address Type')
                ->type('@title', $newLookupTableData['title'])
                ->type('@description', $newLookupTableData['description'])
                ->click('@create-button')
                ->pause($pause['long'])
            ;

            $lookup_address_type = Lookup_address_type::orderBy('id', 'desc')->first();

            $browser->assertPathIs('/nova/resources/lookup_address_types/'.$lookup_address_type->id);
            $this->assertEquals('Blues Boy King', $lookup_address_type->title);
            $this->assertEquals('Lucille', $lookup_address_type->description);
        });

        $this->assertDatabaseHas('lookup_address_types', ['title' => 'Blues Boy King']);
        $this->assertDatabaseHas('lookup_address_types', ['description' => 'Lucille']);
    }


    /**
     * Test that the lookup table record creation fails due to the title field not being filled in
     *
     * @group nova
     * @group novalookuptables
     * @group novaLookuptablesCreationlookuptable
     * @group novalookuptablesInsertNewRecordToLookupTableExpectRequireValidationToFail
     */
    public function testInsertNewRecordToLookupTableExpectRequireValidationToFail()
    {
        $personTryingToLogin = $this->personTryingToLogin;
        $pause               = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])

                ->clickLink('Lookup Address Types')
                ->pause($pause['short'])
                ->assertSee('Create Lookup Address Type')
                ->clickLink('Create Lookup Address Type')
                ->pause($pause['long'])
                ->assertSee('Create Lookup Address Type')
                ->pause($pause['short'])
                ->click('@create-button')
                ->pause($pause['short'])
                ->assertSee('The Title field is required.')
            ;
        });
    }

    /**
     * Test that the lookup table record creation fails due to the description exceeding 255 characters
     *
     * @group nova
     * @group novalookuptables
     * @group novaLookuptablesCreationlookuptable
     */
    public function testInsertNewRecordToLookupTableExpectDescriptionValidationToFail()
    {
        $personTryingToLogin = $this->personTryingToLogin;
        $newLookupTableData  = $this->newLookupTableData;
        $pause               = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $newLookupTableData, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])

                ->clickLink('Lookup Address Types')
                ->pause($pause['short'])
                ->assertSee('Create Lookup Address Type')
                ->clickLink('Create Lookup Address Type')
                ->pause($pause['short'])
                ->assertSee('Create Lookup Address Type')
                ->type('@title', $newLookupTableData['title'])
                ->type('@description', $newLookupTableData['toolongdescription'])
                ->click('@create-button')
                ->pause($pause['long'])
                ->assertSee('The Description may not be greater than 255 characters.')
            ;
        });
    }

    /**
     * Test that the lookup table record creation fails due to the domainbyperson (aka "The User")
     * not having the owner role
     *
     * @group nova
     * @group novalookuptables
     * @group novaLookuptablesCreationlookuptable
     */
    public function testInsertNewRecordToLookupTableExpectNoCreationButtonDueToNotHavingOwnerRole()
    {
        $personTryingToLogin = [
            'email'    => 'bbking@kingofblues.com',
            'password' => 'secret',
        ];
        $pause               = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['short'])

                ->assertDontSeeLink('Lookup Address Types')
                ->visit('/nova/resources/lookup_address_types')
                ->pause($pause['long'])
                ->assertDontSee('Create Lookup Address Type')
            ;
        });
    }
}
