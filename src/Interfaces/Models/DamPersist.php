<?php

namespace Dam\Interfaces\Models;

use Dam\Models\Resource;

interface DamPersist
{

    /*********************** Attributes ***********************/

    public function getId(): string;

    public function getName(): string;

    public function getType(): string;

    public function getMimeType(): string;

    public function getDamModel();

    /*********************** Methods ***********************/
    public function store(array $attributes): DamPersist;

    public function attrsToIndex(): ?array;

}