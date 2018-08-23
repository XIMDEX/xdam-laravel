<?php

namespace Dam\Interfaces\Models;

interface DamUser
{

    public function getAuthGroups(): array;

    public function getAuthUser(): string;

    public function hasPermission($permissions): bool;

}