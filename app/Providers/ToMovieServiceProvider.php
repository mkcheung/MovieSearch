<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ToMovieService;
use GuzzleHttp\Client as GuzzleClient;

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
            $gClient = new GuzzleClient();
            return new ToMovieService($gClient, config('services.tomovies.key'));
        });
    }
}
