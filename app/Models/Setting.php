<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group'
    ];

    public static function get($key = '', $default = null)
    {
        return Cache::rememberForever('settings.' . $key, function () use ($key, $default) {
            $setting = Setting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    // Ayar oluştur veya güncelle ve cache'i güncelle
    public static function set($key, $value, $group = 'general')
    {
        $setting = Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );

        Cache::forever('settings.' . $key, $value);

        return $setting;
    }

    // Bir grup altındaki tüm ayarları getir
    public static function getGroup($group = 'general')
    {
        return Cache::rememberForever('settings.group.' . $group, function () use ($group) {
            return Setting::where('group', $group)->get()->pluck('value', 'key')->toArray();
        });
    }

    // Bir grup altındaki tüm ayarları sil
    public static function forgetGroup($group = 'general')
    {
        Cache::forget('settings.group.' . $group);
    }

    // Tüm ayarları sil
    public static function clearCache()
    {
        Cache::forget('settings');
    }

    // Bir grup altındaki tüm ayarları güncelle
    public static function setGroup($group, $settings)
    {
        foreach ($settings as $key => $value) {
            Setting::set($key, $value, $group);
        }

        Setting::forgetGroup($group);
    }
}
