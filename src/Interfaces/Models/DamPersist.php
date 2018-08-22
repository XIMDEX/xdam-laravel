<?php

namespace Dam\Interfaces\Models;

interface DamPersist
{

    /*********************** Attributes ***********************/

    public function getId(): string;


    /*********************** Methods ***********************/
    public function store(array $attributes): DamPersist;

    public function attrsToIndex(): array;

}