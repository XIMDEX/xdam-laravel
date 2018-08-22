<?php

namespace Dam\Events;

use Dam\Interfaces\Models\DamPersist;
use Illuminate\Queue\SerializesModels;

class ResourceSaved
{
    use SerializesModels;

    public $resource;

    public function __construct(DamPersist $resource)
    {
        $this->resource = $resource;
    }

}