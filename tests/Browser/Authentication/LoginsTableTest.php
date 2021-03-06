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
 * @link       https://lasallesoftware.ca Blog, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Authentication;

// LaSalle Software classes
use Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\Models\Uuid;
use Lasallesoftware\Librarybackend\Authentication\Models\Login;

// Laravel Dusk
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginsTableTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $personTryingToLogin;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrarybackend:customseed');

        $this->personTryingToLogin = [
            'email'    => 'bob.bloom@lasallesoftware.ca',
            'password' => 'secret',
        ];
    }

    /**
     * Test that the updates fields are actually updated on a request after the login request is done.
     *
     * When someone logs in successfully, the subsequent requests are "checked" for auth. Laravel's GuardHelper trait
     * contains the check() method. This check() method calls the user() method in LasalleGuard.php. This user() method
     * is the one that actually does the logins db table update. There are only two fields that are updated: "updated_at"
     * and "updated_by". It is really the "updated_at" field that matters, because user inactivity is guaged by this
     * field. So I want to test that, essentially, "updated_at" is really updated by the user() method. Whew!
     *
     * @group authentication
     * @group authenticationLoginstable
     * @group authenticationLoginstableUpdatesfieldsareupdatedonsubsequentrequestssuccessfully
     */
    public function testUpdatesFieldsAreUpdatedOnSubsequentRequestsSuccessfully()
    {
        echo "\n**Now testing Tests\Browser\Authentication\LoginsTableTest**";

        $personTryingToLogin = $this->personTryingToLogin;

        // Well, we need to login
        $this->browse(function (Browser $browser) use ($personTryingToLogin) {
            $browser
                ->visit('/login')
                ->type('email',    $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause(5000)
                ->assertPathIs(config('lasallesoftware-librarybackend.web_middleware_default_path'))
                ->assertSee('Personbydomains')
                ->pause(5000)  // THIS PAUSE IS ACTUALLY FOR THE TEST ITSELF! TO PURPOSELY CREATE ENOUGH OF
                                          //  TIME GAP THAT WE CAN OBSERVE THE TIME GAP BETWEEN created_at and UPDATED_AT
                                          //  VISUALLY -- AND TO ENSURE THAT OUR TEST WILL PASS. It's just 5 seconds, but...

                // Ok, good, we are logged in. Nice! So, now, we want to do another request, so let's go to addresses,
                // just for the sake of initiating another request cycle, so that we can test that "updated_by"
                ->clickLink('Addresses')
                //->waitFor('@1-row')
                ->pause(5000)
                ->assertSee('Create Address')
            ;
        });

        // The request generated by going to the Addresses index listing was supposed to update the logins "updated_at" field
        // with the current datetime stamp. I designed this test so that the update happens 5 seconds after the initial
        // logins record created generated by logging in. I don't actually test for this 5 second difference.
        $login = Login::orderBy('id', 'desc')->first();
        $this->assertTrue($login->created_at < $login->updated_at,'***The created_by is NOT greater than updated_at --> huh??? ***');
    }
}
