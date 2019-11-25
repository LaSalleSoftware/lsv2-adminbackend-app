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

namespace Tests\Browser\Nova\ProfileTables\Policies\EmailsTable\Index;


// LaSalle Software
use Tests\Browser\Nova\ProfileTables\ProfileTablesBaseDuskTestCase;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminsTest extends ProfileTablesBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test suppression for admin.
     *
     * Please note that the index listing is controlled by the resource's indexQuery() method!
     *
     * @group nova
     * @group novaprofiletables
     * @group novaprofiletablesPolicies
     * @group novaprofiletablesPoliciesEmails
     * @group novaprofiletablesPoliciesEmailsIndex
     * @group novaprofiletablesPoliciesEmailsIndexAdmins
     */
    public function testAdmins()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\Policies\Emails\Index\TestAdmins**";

        $login = $this->loginAdminDomain1;
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
                ->assertDontSee('Email Addresses')
                ->visit('/nova/resources/emails')
                ->pause($pause['long'])
                ->assertDontSee('Create Email Address')
                ->assertDontSee('bob.bloom@lasallesoftware.ca')
                ->assertDontSee('bbking@kingofblues.com')
                ->assertDontSee('srv@doubletrouble.com')
                ->assertDontSee('muddy.waters@blues.com')
                ->assertDontSee('sidney.bechet@blogtest.ca')
               // ->assertDontSee('robert.johnson@blogtest.ca')  ==> displays in the top right, of course, since he is logged in!!
            ;
        });
    }
}
