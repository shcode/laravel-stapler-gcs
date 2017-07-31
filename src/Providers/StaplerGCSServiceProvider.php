<?php

namespace Shcode\Stapler\Providers;

use Codesleeve\LaravelStapler\Providers\L5ServiceProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class StaplerGCSServiceProvider extends BaseServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $packageRoot = dirname(__DIR__);

        $this->publishes([
            $packageRoot.'/config/gcs.php' => config_path('laravel-stapler/gcs.php')
        ]);

    }

    public function register()
    {
        $packageRoot = dirname(__DIR__);

        $this->mergeConfigFrom($packageRoot.'/config/gcs.php', 'laravel-stapler.gcs');

//        dd(config('laravel-stapler'));
    }
}
