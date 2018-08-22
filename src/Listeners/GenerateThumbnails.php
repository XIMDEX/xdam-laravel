<?php

namespace Dam\Listeners;


use Dam\Events\ResourceSaved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class GenerateThumbnails implements ShouldQueue
{

    /**
     * The name of the connection the job should be sent to.
     *
     * @var string|null
     */
    public $connection = 'beanstalkd';

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'thumbnails';

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    public function handle(ResourceSaved $evt)
    {
        $resource = $evt->resource;

        Artisan::call('thumbnail:create', [
            'model' => get_class($resource),
            'resourceId' => $resource->getId(),
            'storage' => $resource->getThumbnailAbsolutePath()
        ]);
    }

    /**
     * Handle a job failure.
     *
     * @param  ResourceSaved $event
     * @param  \Exception $exception
     * @return void
     */
    public function failed(ResourceSaved $event, $exception)
    {
        Log::error("Failed to create thumbnails: {$exception->getMessage()}");
    }

}