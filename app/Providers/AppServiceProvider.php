<?php

namespace App\Providers;

use App\Utils\MyEncrypt;
use App\Utils\NewToken;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('MyEncrypt', function() {
            return new MyEncrypt();
        });
        $this->app->bind('NewToken', function() {
            return new NewToken();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
