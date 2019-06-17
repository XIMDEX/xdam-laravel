<?php

namespace Dam\Core\Settings;

class ListDamSettings extends Settings
{
    protected $model;
    protected $items;

    function __construct(ListDamModelSetting $model = null)
    {
        $this->setModel($model);
    }

    public function setModel(?ListDamModelSetting $model)
    {
        $this->model = $model;
        return $this;
    }
    public function getModel(): ?ListDamModelSetting
    {
        return $this->model;
    }
}