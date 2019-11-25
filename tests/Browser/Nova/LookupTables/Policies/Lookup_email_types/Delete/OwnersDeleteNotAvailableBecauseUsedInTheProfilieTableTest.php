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
 * @link       https://lasallesoftware.ca
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\LookupTables\Policies\Lookup_email_types\Delete;


// LaSalle Software
use Lasallesoftware\Library\Profiles\Models\Lookup_email_type;
use Tests\Browser\Nova\LookupTables\LookupTablesBaseDuskTestCase;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel classes
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Carbon;

// Laravel facade
use Illuminate\Support\Facades\DB;

class OwnersDeleteNotAvailableBecauseUsedInTheAddressTableTest extends LookupTablesBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test that the an owner can delete.
     *
     * @group nova
     * @group novalookuptables
     * @group novaLookuptablesPolicies
     * @group novaLookuptablesPoliciesLookupemailtypestypes
     * @group novaLookuptablesPoliciesLookupemailtypesDelete
     * @group novaLookuptablesPoliciesLookupemailtypesDeleteOwnersdeletenotavailablebecauseusedintheprofiletable
     */
    public function testOwnersDeleteNotAvailableBecauseUsedInTheProfileTable()
    {
        echo "\n**Now testing Tests\Browser\Nova\LookupTables\Policies\Lookupemailtypes\Delete\TestOwnersDeleteNotAvailableBecauseUsedInTheProfileTable**";


        // ARRANGE
        // (i) need a brand new lookup record, because the first initial records are in the "do not delete" list
        $this->insertGenericLookupRecord('lookup_email_types');

        // (ii) create a record in the profile db table, using the new profile record
        $this->insertTestRecordIntoLookup();


        // ACT and ASSERT
        // The deletion icon should display for owners
        $login        = $this->loginOwnerBobBloom;
        $pause        = $this->pause();
        $lookupLastId = $this->getLookupTableLastId();

        $this->browse(function (LaSalleBrowser $browser) use ($login, $pause, $lookupLastId) {
            $browser
                ->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertPathIs('/nova/resources/personbydomains')
                ->assertSee('Personbydomains')
                ->assertSee('Lookup Email Types')
                ->clickLink('Lookup Email Types')
                ->pause($pause['long'])
                ->assertSee('Create Lookup Email Type')
                ->assertVisible('@'.$lookupLastId.'-row')
                ->assertMissing('@'.$lookupLastId.'-delete-button')
            ;
        });
    }

    private function insertTestRecordIntoLookup()
    {
        DB::table('emails')->insert([
            'lookup_email_type_id'  => $this->getLookupTableLastId(),
            'email_address'         => 'test@test.test',
            'description'           => 'A specially inserted record for testing!',
            'comments'              => 'A specially inserted record for testing!',
            'uuid'                  => null,
            'created_at'            => Carbon::now(),
            'created_by'            => 1,
            'updated_at'            => null,
            'updated_by'            => null,
            'locked_at'             => null,
            'locked_by'             => null,
        ]);
    }

    private function getLookupTableLastId()
    {
        $lookupRecord = Lookup_email_type::orderBy('id', 'desc')->first();
        return $lookupRecord->id;
    }
}
