<?php

use App\Models\Setting;

if (!function_exists('settings')) {
    /**
     * Get or set settings
     *
     * @param string|array|null $key
     * @param mixed $default
     * @return mixed
     */
    function settings($key = null, $default = null)
    {
        // If key is null, return all settings (not implemented for simplicity)
        if (is_null($key)) {
            return null;
        }

        // If key is an array, set multiple settings
        if (is_array($key)) {
            Setting::setMany($key);
            return null;
        }

        // Get a single setting
        return Setting::get($key, $default);
    }
}
