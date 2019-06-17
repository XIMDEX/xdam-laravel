<?php

namespace Dam\Core\Settings;

class DamSettings extends Settings
{
    protected $search;
    protected $pager;
    protected $list;

    function __construct($search = null, $pager = null, ListDamSettings $list = null)
    {
        $this->setList($list);
    }

    public function setList(?ListDamSettings $list)
    {
        $this->list = $list;
        return $this;
    }
    public function getList(): ?ListDamSettings
    {
        return $this->list;
    }
}