<?php

namespace Dam\Models;

use Dam\Interfaces\Models\DamPersist;

class FileSystem implements DamPersist
{

    /******************************************************************************************************************
     * DAM PERSIST INTERFACE
     ******************************************************************************************************************/

    /*********************** Attributes ***********************/

    public function getId(): string
    {
    }

    public function getName(): string
    {
    }

    public function getType(): string
    {
    }


    public function getMimeType(): string
    {
    }

    public function getFileName(): string
    {
    }

    public function getPath(): string
    {
    }

    public function getAbsolutePath(): string
    {
    }

    public function getThumbnailAbsolutePath(): string
    {
    }

    public function getThumbExtension(): string
    {
    }

    public function getDamModel()
    {
    }

    /*********************** Methods ***********************/

    public function store(array $attributes): DamPersist
    {
    }

    public function attrsToIndex(): ?array
    {
    }

    private function getAuthGroups($context)
    {
    }

    private function getAuthUsers($context)
    {
    }
}