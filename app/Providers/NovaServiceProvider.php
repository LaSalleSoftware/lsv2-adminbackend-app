<?php

namespace App\Providers;

use Laravel\Nova\Nova;
use Laravel\Nova\Cards\Help;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\NovaApplicationServiceProvider;

use Illuminate\Support\Facades\Auth;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // https://nova.laravel.com/docs/2.0/resources/date-fields.html
        Nova::userTimezone(function (Request $request) {
            return $request->user()->timezone;
        });
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                //->withAuthenticationRoutes()
                //->withPasswordResetRoutes()
                ->register();                
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * INCLUDING THE "testing" ENVIRONMENT!
     * see Laravel\Nova\AuthorizesRequests trait, used in Laravel\Nova\Nova
     *
     * @return void
     */
    protected function gate()
    {
        if (env('APP_ENV') == "testing") {

            // These are emails that are thumbs up to access Nova in the "testing" environment
            Gate::define('viewNova', function ($user) {
                return in_array($user->email, [
                    'bob.bloom@lasallesoftware.ca',
                    'bbking@kingofblues.com',
                    'srv@doubletrouble.com',
                    'sidney.bechet@blogtest.ca',
                    'robert.johnson@blogtest.ca',
                    'satchmo@blues.com'
                ]);
            });

        } else {

            // These are the emails that are thumbs up to access Nova in all environments that are not "testing" and not "local".
            // There are no restrictions for "local" environments
            Gate::define('viewNova', function ($user) {
                return in_array($user->email, [
                    'bob.bloom@lasallesoftware.ca',
                ]);
            });
        }
    }

    /**
     * Get the cards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new Help,
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}