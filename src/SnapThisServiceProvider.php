<?php

namespace STS\SnapThis;

use Illuminate\Support\ServiceProvider;

class SnapThisServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/snapthis.php' => config_path('snapthis.php'),
            ], 'config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/snapthis.php', 'snapthis');

        $this->app->bind(Client::class, function($app)
        {
            return (new Client($app['config']->get('snapthis.key')))->applyDefaults();
        });
    }


}
