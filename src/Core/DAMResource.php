<?php

namespace Dam\Core;

use Dam\Models\Resource;
use Dam\Interfaces\Models\DamPersist;

class DAMResource
{

    public static function store(DamPersist $resource, Resource $resIndex, array $attributes): DamPersist
    {
        $resource = $resource->store($attributes);

        if ($resource !== false) {
            $attrs = $resource->attrsToIndex();
            $saved = $resIndex->save($attrs);
            if (!$saved) {
                throw new \ErrorException('Model indexation not saved', 400);
            }
        }
        return $resource;
    }

}