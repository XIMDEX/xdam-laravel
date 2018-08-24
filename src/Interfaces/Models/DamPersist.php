<?php

namespace Dam\Interfaces\Models;

interface DamPersist
{

    /*********************** Attributes ***********************/

    public function getId(): string;

    public function getName(): string;

    public function getType(): string;

    public function getMimeType(): string;

    /*********************** Methods ***********************/
    public function store(array $attributes): DamPersist;

    public function attrsToIndex(): array;

}