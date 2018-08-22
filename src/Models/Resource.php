<?php

namespace Dam\Models;

use Xfind\Models\Item;

abstract class Resource extends Item
{

    public static $rules = [
        'name' => ['type' => 'string', 'required' => true],
        'context' => ['type' => 'string', 'required' => true],
        'owner' => ['type' => 'string', 'required' => false],
        'auth_users' => ['type' => 'string', 'required' => true],
        'auth_groups' => ['type' => 'string', 'required' => true],
        'preview' => ['type' => 'string', 'required' => false],
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

        $this->beforeSave();
        $res = $this->createOrUpdate();
        $this->afterSave();

        return $res;
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

    protected function beforeSave()
    {

    }

    protected function afterSave()
    {

    }

}
