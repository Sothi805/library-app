<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'description'];

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return match($setting->type) {
            'integer' => (int) $setting->value,
            'boolean' => (bool) $setting->value,
            'float' => (float) $setting->value,
            default => $setting->value,
        };
    }
}
