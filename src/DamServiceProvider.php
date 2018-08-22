<?php

namespace Dam;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Dam\Console\Commands\CreateThumbnail;
use Dam\Events\ResourceSaved;
use Dam\Listeners\GenerateThumbnails;

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
            CreateThumbnail::class
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
