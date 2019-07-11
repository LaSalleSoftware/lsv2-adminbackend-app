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
 * @link       https://lasallesoftware.ca \Lookup_address_type;log, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Tests\Browser\Nova\BlogTables\AdminForms\Posts\Delete;

// LaSalle Software
use Tests\Browser\Nova\BlogTables\BlogTablesBaseDuskTestCase;
use Lasallesoftware\Library\Dusk\LaSalleBrowser;

// Laravel class
use Illuminate\Foundation\Testing\DatabaseMigrations;

class IsSuccessfulTest extends BlogTablesBaseDuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('lslibrary:customseed');
        $this->artisan('lsblogbackend:blogcustomseed');

    }

    /**
     * Test that the delete is successful
     *
     * @group nova
     * @group novablogtables
     * @group novablogtablesadminforms
     * @group novablogtablesadminformsposts
     * @group novablogtablesadminformspostsdelete
     * @group novablogtablesadminformspostsdeleteissuccessful
     */
    public function testDeleteIsSuccessful()
    {
        echo "\n**Now testing Tests\Browser\Nova\BlogTables\AdminForms\Posts\Delete\TestDeleteIsSuccessful**";

        $login            = $this->loginOwnerBobBloom;
        $postTitles       = $this->postTitles;
        $postupdateTitles = $this->postupdateTitles;
        $pause            = $this->pause;

        $this->browse(function (LaSalleBrowser $browser) use ($login, $postTitles, $postupdateTitles, $pause) {
            $browser->visit('/login')
                ->type('email', $login['email'])
                ->type('password', $login['password'])
                ->press('Login')
                ->pause($pause['shortest'])
                ->assertPathIs('/nova')
                ->assertSee('Dashboard')
                ->clickLink('Posts')
                ->pause($pause['shortest'])
                ->waitFor('@2-row')
                ->click('@2-delete-button')
                ->pause($pause['shortest'])
                ->click('#confirm-delete-button')
                ->pause($pause['medium'])
                ->assertMissing('@2-row')
            ;

            $this->assertDatabaseMissing('posts', ['title' => $postTitles[2]]);
            $this->assertDatabaseMissing('postupdates', ['title' => $postupdateTitles[1]]);
            $this->assertDatabaseMissing('postupdates', ['title' => $postupdateTitles[3]]);

        });
    }
}
