<?php

namespace Dam\Core\Settings;

abstract class Settings
{
    protected $fillable = [];
    protected $exclude = [];
    protected $nullable = [];

    public function getFillable(): array
    {
        $attributes = $this->fillable;
        if (count($attributes) === 0) {
            $attributes = get_object_vars($this);
        }

        return $this->excludeFields($attributes);
    }

    protected function excludeFields(array $fields): array
    {
        $exclude = $this->exclude + ['fillable', 'exclude', 'nullable'];
        foreach ($exclude as $rule) {
            if (array_key_exists($rule, $fields)) {
                unset($fields[$rule]);
            }
        }

        return $fields;
    }

    protected function isNullable(string $key): bool
    {
        return in_array($key, $this->nullable);
    }

    public function toArray(): array
    {
        $attributes = $this->getFillable();

        foreach ($attributes as $attribute => $value) {
            if (!is_null($value) || $this->isNullable($attribute)) {
                if ($value instanceof Settings) {
                    $value = $value->toArray();
                }
                $result[$attribute] = $value;
            }
        }

        return $result;
    }
}