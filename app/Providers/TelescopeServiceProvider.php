<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider {

    /**
     * Register any application services.
     */
//    public function register(): void
//    {
//        // Telescope::night();
//
//        $this->hideSensitiveRequestDetails();
//
//        Telescope::filter(function (IncomingEntry $entry) {
//            if ($this->app->environment('local')) {
//                return true;
//            }
//
//            return $entry->isReportableException() ||
//                   $entry->isFailedRequest() ||
//                   $entry->isFailedJob() ||
//                   $entry->isScheduledTask() ||
//                   $entry->hasMonitoredTag();
//        });
//    }
    public function register(): void {
        // Only register Telescope in the local environment
        if ($this->app->environment('local')) {
            parent::register();  // This registers Telescope and its services

            // Optionally enable night mode for Telescope UI
            // Telescope::night();

            $this->hideSensitiveRequestDetails();

            Telescope::filter(function (IncomingEntry $entry) {
                return true; // Log all entries in local environment
            });
        }
    }


    /**
     * Prevent sensitive request details from being logged by Telescope.
     */
//    protected function hideSensitiveRequestDetails(): void {
//        if ($this->app->environment('local')) {
//            return;
//        }
//
//        Telescope::hideRequestParameters(['_token']);
//
//        Telescope::hideRequestHeaders([
//            'cookie',
//            'x-csrf-token',
//            'x-xsrf-token',
//        ]);
//    }

    protected function hideSensitiveRequestDetails(): void {
        // Only hide sensitive details in production or other non-local environments
        if (!$this->app->environment('local')) {
            Telescope::hideRequestParameters(['_token']);

            Telescope::hideRequestHeaders([
                'cookie',
                'x-csrf-token',
                'x-xsrf-token',
            ]);
        }
    }


    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     */
//    protected function gate(): void {
//        Gate::define('viewTelescope', function ($user) {
//            return in_array($user->email, [
//                //
//            ]);
//        });
//    }
    protected function gate(): void {
        // You can define gates here to restrict Telescope access in non-local environments,
        // but since we are not registering Telescope in production, this gate is redundant.
    }

}
