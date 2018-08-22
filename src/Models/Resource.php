<?php

namespace Dam\Models;

use Xfind\Models\Item;

abstract class Resource extends Item
{

    const TYPE = 'xresource';

    public static $rules = [
        'name' => ['type' => 'string', 'required' => true],
        'context' => ['type' => 'string', 'required' => true],
        'owner' => ['type' => 'string', 'required' => true],
        'auth_users' => ['type' => 'string', 'required' => true],
        'auth_groups' => ['type' => 'string', 'required' => true],
        'preview' => ['type' => 'string', 'required' => true],
        'type' => ['type' => 'string', 'required' => true],
        'description' => ['type' => 'string', 'required' => false],
        'tags' => ['type' => 'array', 'required' => false]
    ];

    protected $highlight_fields = [
    ];

    public function __construct()
    {
        $this->facets = array_merge($this->facets, [
            'type'
        ]);
        static::$rules = array_merge(static::$rules, self::$rules);
        parent::__construct();
    }


    /*********************** Methods ***********************/
    public function save(array $attributes): bool
    {
        foreach ($attributes as $attribute => $value) {
            $this->$attribute = $value;
        }
        return $this->createOrUpdate();
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

        $sort = array_merge($sort, ['date' => 'desc'], $this->sort);

        $query = "($query) AND type:" . static::TYPE;

        return parent::find($query, $sort);
    }


}
