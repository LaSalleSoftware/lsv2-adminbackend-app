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

namespace Tests\Browser\Nova\LoginsTable\Policies\Index;

// LaSalle Software
use Tests\Browser\Nova\LoginsTable\LoginsTableBaseDuskTest;
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminsTest extends LoginsTableBaseDuskTest
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test that an admin cannot see the index page.
     *
     * @group nova
     * @group novaLogin
     * @group novaLoginPolicies
     * @group novaLoginPoliciesIndex
     * @group novaLoginPoliciesIndexAdmins
     */
    public function testAdmins()
    {
        echo "\n**Now testing Tests\Browser\Nova\LoginsTable\Policies\Index\TestAdmins**";

        $this->insertTestRecordIntoLoginsExcludeAdminLoginTable();

        $personTryingToLogin  = $this->loginAdminDomain1;
        $pause                = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser
                ->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->assertMissing('Logins')

                // Can go to the index view via direct URL, because there is no policy to prevent it. Instead,
                // we control what records are listed via IndexQuery
                ->visit('/nova/resources/logins/')
                ->pause($pause['long'])
                ->assertMissing('@1-row')
                ->assertMissing('@2-row')
                ->assertMissing('@3-row')
                ->assertMissing('@4-row')

            ;
        });
    }
}
