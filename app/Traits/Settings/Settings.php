<?php

namespace App\Traits\Settings;

use MOIREI\ModelData\HasData;

trait Settings
{
    // Use the HasData trait to manage model data
    use HasData;
    
    // Define the model data attributes that the trait will handle
    protected $model_data = [
        'settings', 'meta',
    ];

    /**
     * Set settings data for the model.
     * 
     * If a specific key is provided, it will set the data for that key.
     * If no key is provided, it will set all data passed in the params.
     *
     * @param mixed $params The settings data to set.
     * @param string|null $key The specific key within the settings to set (optional).
     * @return $this
     */
    public function set_settings($params, $key = null)
    {
        // If a key is provided, set the data for that key
        if ($key) {
            $this->data->set($key, $params);
        } else {
            // Otherwise, set all the data
            $this->data->set($params);
        }

        return $this;
    }

    /**
     * Scope query to check if a setting exists.
     * 
     * This checks if the specified setting (within the 'settings' JSON column) has a non-empty value.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder.
     * @param string $settingName The name of the setting to check.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasSetting($query, $settingName)
    {
        // Query where the setting exists and is not empty (length > 0)
        return $query->where(function ($query) use ($settingName) {
            $query->whereJsonLength('settings->' . $settingName, '>', 0);
        });
    }
}
