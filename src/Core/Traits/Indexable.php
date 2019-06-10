<?php

namespace Dam\Core\Traits;

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

    protected function fromStorage(Model $repository)
    {
        $disk = $repository->type;

        $configs = array_merge([
            'driver' => $disk
        ], $repository->settings_json);

        $fileSystem = new FileSystem($configs, $disk);

        $fileSystem->findFiles(function ($file) use ($repository) {
            $indexableModel = static::$baseIndexableNamespace . '\\' . ucfirst($file['type']);

            if (!class_exists($indexableModel)) {
                $indexableModel = static::$baseIndexableNamespace . '\\' . static::$fallbackModel;
            }

            $file = array_merge($file, [
                'repository_id' => $repository->id,
                'context' => $repository->context,
                'context_resource' => $file['fullpath'],
                'auth_groups' => [
                    '*'
                ],
                'auth_users' => [
                    '*'
                ],
            ]);

            $indexableModel::createOrUpdate($file, "context_resource");
        });
    }
}
