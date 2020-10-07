<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Let laravel know the public path of your application
        $this->app->bind('path.public', function () {
            return base_path('../public_html');
            // Change httpdocs to public_html if you are using cpanel
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
        View::composer('*','App\Http\ViewComposers\ViewComposer');
    }
}
