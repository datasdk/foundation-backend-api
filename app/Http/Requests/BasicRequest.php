<?php

namespace App\Http\Requests;

use Orion\Http\Requests\Request;

class BasicRequest extends Request
{
    protected $rules = [];

    protected $storeRules = [];

    protected $updateRules = [];

    protected static $dynamicStoreRules = [];

    protected static $dynamicUpdateRules = [];



    public function commonRules(): array
    {
        return $this->normalizeRules($this->rules);
    }


    public function storeRules(): array
    {
        return $this->normalizeRules(array_merge($this->storeRules, self::$dynamicStoreRules));
    }


    public function updateRules(): array
    {
        return $this->normalizeRules(array_merge($this->updateRules, self::$dynamicUpdateRules));
    }


    public static function addDynamicRules(array $rules)
    {
        self::addDynamicStoreRules($rules);
        self::addDynamicUpdateRules($rules);
    }


    public static function addDynamicStoreRules(array $rules)
    {
        self::$dynamicStoreRules = array_merge(self::$dynamicStoreRules, $rules);
    }


    public static function addDynamicUpdateRules(array $rules)
    {
        self::$dynamicUpdateRules = array_merge(self::$dynamicUpdateRules, $rules);
    }


    protected function normalizeRules(array $rules, string $prefix = ''): array
    {
        $normalized = [];

        foreach ($rules as $key => $value) {
            $name = $prefix === '' ? (string) $key : $prefix . '.' . $key;

            if (is_array($value) && !array_is_list($value)) {
                $normalized = array_merge($normalized, $this->normalizeRules($value, $name));
                continue;
            }

            $normalized[$name] = $value;
        }

        return $normalized;
    }
    
}
