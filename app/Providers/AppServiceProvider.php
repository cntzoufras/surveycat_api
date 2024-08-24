<?php

namespace App\Providers;

use App\Repositories\Survey\SurveyRepository;
use App\Repositories\Survey\SurveyRepositoryInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void {
        if ($this->app->environment('local')) {
            $this->app->register(\App\Providers\TelescopeServiceProvider::class);
        }
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        $this->app->bind(SurveyRepositoryInterface::class, SurveyRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        //
    }
}
