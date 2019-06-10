<?php

namespace Dam\Models;

use Xfind\Database\SolrEloquent\Model;

class Resource extends Model
{
    protected static $indexModel = \Dam\Models\Solr\Resource::class;

    protected $fillable = [
        'id', //
        'repository_id', //
        'context_resource', //
        'name', //
        'filename',
        'path',
        'context', //
        'owner',
        'auth_users',
        'auth_groups',
        'preview',
        'type', //
        'mime_type', //
        'description', //
        'tags', //
        'mimetype', //
        'extension', //
        'path', //
    ];

    protected $casts = [
        'tags' => 'array',
        'description' => 'string'
    ];

    public function setPathAttribute(?string $value)
    {
        $path = $value ?? '';
        $path = str_replace_first('.', '', $path);
        $this->attributes['path'] = $path;
        return $this;
    }

    public function setContextAttribute($value)
    {
        $this->attributes['context'] = $value;
        $this->setIdAttribue($this->id);
        return $this;
    }

    public function setContextResourceAttribute($value)
    {
        $this->attributes['context_resource'] = md5("{$this->attributes['repository_id']}_{$value}");
        return $this;
    }

    public function setIdAttribue(?string $value = null)
    {
        $context = is_null($this->repository_id) ? '' : "{$this->repository_id}_";
        $this->attributes['id'] = is_null($value) ? uniqid($context, true) : "{$context}_{$value}";
        return $this;
    }

    public static function create($attributes)
    {
        if (is_array($attributes)) {
            $data = new Resource($attributes);
        } elseif ($attributes instanceof \Xfind\Database\SolrEloquent\Model) {
            $data = $attributes;
        } else {
            throw new \Exception("Invalid data");
        }

        $item = $data->solr->save($data->toArray());

        if (!$item) {
            throw new \Exception("Failed to create resource: {$data->name} from context: {$data->context}");
        }

        return $data;
    }

    public static function createOrUpdate(array $attributes, ?string ...$query)
    {
        $data = new Resource($attributes);

        if (is_null($query)) {
            $query = "id:\"{$data->id}\"";
        }

        if (is_array($query)) {
            $_query = [];
            foreach ($query as $q) {
                $_query[] = "{$q}: \"{$data->$q}\"";
            }

            $query = implode(" AND ", $_query);
        }

        $result = $data->solr->one($query);

        if (!is_null($result)) {
            $data->id = $result['id'];
        }

        $result = static::create($data);
        return $result;
    }

    // TODO Implements methods

    /*********************** Attributes ***********************/

    public function getId(): ?string
    { }

    public function getName(): ?string
    { }

    public function getType(): ?string
    { }

    public function getMimeType(): ?string
    { }

    public function getDamModel()
    { }

    /*********************** Methods ***********************/

    public function attrsToIndex(): ?array
    { }
}