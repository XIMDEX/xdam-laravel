<?php

namespace Dam\Models;

use Xfind\Models\Item;

abstract class Resource extends Item
{
    public static $search = 'name';

    protected static $rules = [
        'id' => ['type' => 'string', 'required' => true],
        'name' => ['type' => 'string', 'required' => true],
        'context' => ['type' => 'array', 'required' => true],
        'owner' => ['type' => 'string', 'required' => false],
        'auth_users' => ['type' => 'array', 'required' => true],
        'auth_groups' => ['type' => 'array', 'required' => true],
        'preview' => ['type' => 'string', 'required' => false],
        'type' => ['type' => 'string', 'required' => true],
        'description' => ['type' => 'string', 'required' => false],
        'tags' => ['type' => 'array', 'required' => false],
        'mime_type' => ['type' => 'string', 'required' => false],
        'extension' => ['type' => 'string', 'required' => false],
    ];

    protected static $facets = [
        'type',
        'mime_type',
        'extension',
        'owner',
        'type',
        'tags',
    ];

    protected $highlight_fields = [
    ];

    public function __construct()
    {
        static::$facets = array_merge(static::$facets, self::$facets);
        static::$rules = array_merge(static::$rules, self::$rules);
        parent::__construct();
    }


    /*********************** Methods ***********************/
    public function save(array $attributes): bool
    {
        $saved = false;
        $this->beforeSave($attributes);

        foreach ($attributes as $attribute => $value) {
            $this->$attribute = $value;
        }
        ['valid' => $valid, 'errors' => $errors] = $this->validate();

        if ($valid) {
            $saved = $this->createOrUpdate();
            $this->afterSave($saved);
        } else {
            $this->remove($this->id);
            $message = '[ ' . implode('; ', $errors) . " ], Resource with id: {$this->id} deleted";
            throw new \ErrorException($message);
        }

        return $saved;
    }

    public function remove(string $id): bool
    {
        return $this->delete($id) ? true : false;
    }

    public function find($query = null, array $sort = [])
    {
        if (is_null($query)) {
            $query = $this->query;
        }        

        $sort = array_merge($sort, $this->defaultSort, $this->sort);

        return parent::find($query, $sort);
    }

    protected function beforeSave(array &$attributes)
    {
    }

    protected function afterSave($saved)
    {
    }
}
