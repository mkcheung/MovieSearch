<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ToMovieService;

class ToMovieServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ToMovieService::class, function(){
            return new ToMovieService(config('services.tomovies.key'));
        });
    }
}
