<?php

namespace Dam\Models;

use Xfind\Core\Database\SolrEloquent\Model;

class Resource extends Model
{
    protected $fillable = [
        'id', //
        'repository_id', //
        'name', //
        'filename',
        'path',
        'owner',
        'auth_users',
        'auth_groups',
        'type', //
        'description', //
        'tags', //
        'mimetype', //
        'extension', //
        'created_at', //
        'updated_at',
        'cache_hash'
    ];

    protected $casts = [
        'description' => 'string'
    ];

    protected $facets = [
        'extension'
    ];

    protected $appends = [
        'preview'
    ];

    public function setIdAttribue(?string $value = null)
    {
        $context = is_null($this->repository_id) ? '' : "{$this->repository_id}_";
        $this->attributes['id'] = is_null($value) ? uniqid($context, true) : "{$context}_{$value}";
        return $this;
    }

    public function getPreviewAttribute()
    {
        $url = config('xdam.resource.preview');
        if (\Route::has($url)) {
            $url = route($url, ['id' => $this->id]);
        }
        return $url;
    }

    /**
     * Delete the model from the database.
     *
     * @return bool|null
     *
     * @throws \Exception
     */
    public function remove()
    {
        $result = parent::remove();

        if ($result) {
            $this->deleteResource();
        }

        return $result;
    }

    protected function deleteResource()
    { }
}