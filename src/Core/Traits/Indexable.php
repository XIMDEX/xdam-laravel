<?php

namespace Dam\Core\Traits;

use Dam\Models\Resource;
use Dam\Core\FileSystem\FileSystem;
use Illuminate\Database\Eloquent\Model;

trait Indexable
{
    protected static $baseIndexableNamespace = '\\Dam\\Models';
    protected static $fallbackModel = 'Resource';

    public function setIndexableModel(string $indexableModel)
    {
        static::$indexableModel = $indexableModel;
        return $this;
    }

    protected function loadRepository(Model $repository)
    {
        $disk = $repository->type;

        $configs = array_merge([
            'driver' => $disk
        ], $repository->settings_json);

        return new FileSystem($configs, $disk);
    }

    protected function getPreview(Model $repository, Resource $resource)
    {
        $fileSystem = $this->loadRepository($repository);
        $image = $fileSystem->cachePreview($resource->path, $resource->type, $resource->cache_hash);
        if (isset($image->cachekey)) {
            $resource->cache_hash = $image->cachekey;
            $resource->save();
        }
        return $image->response();
    }

    protected function remove(Model $repository, Resource $resource)
    {
        $fileSystem = $this->loadRepository($repository);
        return $fileSystem->delete($resource->path);
    }

    protected function fromStorage(Model $repository)
    {
        $fileSystem = $this->loadRepository($repository);

        $fileSystem->findFiles(function ($file) use ($repository) {
            $indexableModel = static::$baseIndexableNamespace . '\\' . ucfirst($file['type']);

            if (!class_exists($indexableModel)) {
                $indexableModel = static::$baseIndexableNamespace . '\\' . static::$fallbackModel;
            }

            $file = array_merge($file, [
                'repository_id' => $repository->id,
                'path' => $file['fullpath'],
                'filename' => $file['name'],
                'auth_groups' => [
                    '*'
                ],
                'auth_users' => [
                    '*'
                ],
            ]);

            $filters['path'] = $file['path'];
            $filters['filename'] = $file['filename'];
            $filters['repository_id'] = $file['repository_id'];

            $indexableModel::updateOrCreate($filters, $file);
        });
    }
}
