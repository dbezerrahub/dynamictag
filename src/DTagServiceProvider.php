<?php

namespace Dob\DTag;

use Illuminate\Support\ServiceProvider;

class DTagServiceProvider extends ServiceProvider
{
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
    	include __DIR__.'/routes.php';
    	$this->app->make('Dob\DTag\DTagController');
    }
}
