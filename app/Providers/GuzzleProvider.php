<?php
/**
 * Created by PhpStorm.
 * User: marscheung
 * Date: 1/20/19
 * Time: 11:24 PM
 */
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;

class GuzzleProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind('Client', function () {
            return new Client;
        });
    }

}