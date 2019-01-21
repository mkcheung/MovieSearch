<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ToMovieService;
use App\Movie;
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
            $movie = new Movie();
            return new ToMovieService($movie, $gClient, config('services.tomovies.key'));
        });
    }
}
