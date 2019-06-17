<?php

namespace Dam;

use Dam\Events\ResourceSaved;
use Dam\Listeners\GenerateThumbnails;
use Intervention\Image\Facades\Image;
use Illuminate\Foundation\AliasLoader;
use Dam\Console\Commands\ReindexCommand;
use Dam\Console\Commands\CreateThumbnail;
use Dam\Console\Commands\RegenerateThumbnails;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class DamServiceProvider extends EventServiceProvider
{

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ResourceSaved::class => [
            GenerateThumbnails::class,
        ],
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        $this->commands([
            CreateThumbnail::class,
            ReindexCommand::class,
            RegenerateThumbnails::class
        ]);
        $this->loadViewsFrom(__DIR__ . "/../resources/views", 'xdam');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'xfind');

        $this->publishes([
            __DIR__ . '/../public/' => public_path('vendor/xdam'),
            __DIR__ . '/../resources/views' => resource_path('views/vendor/xdam'),
            __DIR__ . '/../config/config.php' => config_path('xdam.php')
        ]);

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang'),
        ], 'langs');

        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('xdam.php'),
        ], 'xadm.settings');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/config.php',
            'xdam'
        );

        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Image', Image::class);
        });
    }
}
