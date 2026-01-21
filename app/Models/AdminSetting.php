<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
    protected $fillable = [
        'setting_key',
        'setting_value',
        'setting_type',
        'description',
        'category',
    ];

    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('setting_key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return self::castValue($setting->setting_value, $setting->setting_type);
    }

    /**
     * Set a setting value by key
     */
    public static function set($key, $value)
    {
        $setting = self::firstOrNew(['setting_key' => $key]);
        $setting->setting_value = is_bool($value) ? ($value ? '1' : '0') : (string) $value;
        $setting->save();
        return $setting;
    }

    /**
     * Cast value based on type
     */
    private static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return (bool) $value;
            case 'integer':
                return (int) $value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * Get all settings grouped by category
     */
    public static function getByCategory()
    {
        return self::orderBy('category')->orderBy('setting_key')->get()->groupBy('category');
    }
}
