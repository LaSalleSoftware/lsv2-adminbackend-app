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
 * @link       https://lasallesoftware.ca \lookup_social_type;log, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\ProfileTables\SocialsTable\Details;

// LaSalle Software
use Tests\LaSalleDuskTestCase;
use Lasallesoftware\Librarybackend\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class IsSuccessfulTest extends LaSalleDuskTestCase
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
     * Test that the details view is successful
     *
     * @group nova
     * @group novaprofiletables
     * @group novasocial
     * @group novasocialdetailsissuccessful
     */
    public function testDetailsViewIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\ProfileTables\SocialsTable\Details\IsSuccessfulTest**";

        $personTryingToLogin = $this->personTryingToLogin;
        $pause               = $this->pause();

        $this->browse(function (LaSalleBrowser $browser) use ($personTryingToLogin, $pause) {
            $browser->visit('/login')
                ->type('email', $personTryingToLogin['email'])
                ->type('password', $personTryingToLogin['password'])
                ->press('Login')
                ->pause($pause['long'])
                ->assertSee('Personbydomains')
                ->clickLink('Social Sites')
                ->pause($pause['long'])
                ->assertVisible('@1-row')

                ->assertVisible('@1-view-button')
                ->click('@1-view-button')
                ->pause($pause['long'])
                ->assertSee('Social Site Details')
                ->assertPathIs('/nova/resources/socials/1')
                ->assertSee('https://www.allaboutbluesmusic.com/delta-blues/')
            ;
        });
    }
}
