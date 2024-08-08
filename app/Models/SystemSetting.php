<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Get the value of the setting.
     *
     * @param string $key
     * @return string
     */
    public static function get(string $key): string
    {
        $setting = self::where('key', $key)->first();

        return $setting ? $setting->value : '';
    }

    /**
     * Set the value of the setting.
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public static function set(string $key, string $value): void
    {
        $setting = self::where('key', $key)->first();

        if ($setting) {
            $setting->update(['value' => $value]);
        } else {
            self::create(['key' => $key, 'value' => $value]);
        }
    }

    /**
     * Remove the setting.
     *
     * @param string $key
     * @return void
     */
    public static function remove(string $key): void
    {
        self::where('key', $key)->delete();
    }

    /**
     * Check if the setting exists.
     *
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return self::where('key', $key)->exists();
    }

    /**
     * Get all settings.
     *
     * @return array
     */
    public static function allSettings(): array
    {
        return self::all()->pluck('value', 'key')->toArray();
    }

    /**
     * Get all settings as a collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function allSettingsCollection()
    {
        return self::all()->pluck('value', 'key');
    }

    /**
     * Store multiple settings.
     * 
     * @param array $settings
     */
    public static function storeMultiple(array $settings): void
    {
        foreach ($settings as $key => $value) {
            self::set($key, $value);
        }
    }

    /**
     * Store settings as array from textarea request.
     * 
     * @param string $key
     * @param string $settings
     */
    public static function storeFromTextarea(string $key, string $settings): void
    {
        $settings = explode("\n", $settings);
        $settings = array_map('trim', $settings);

        self::set($key, json_encode($settings));
    }

    /**
     * Get divisions as array or string.
     * 
     * @param string $key
     * @return array || @return string
     */
    public static function getJsonDecoded(string $key, string $type)
    {
        $value = self::get($key);
        
		if ($value) {
			$value = json_decode( $value, true );

            switch ($type) {
                case 'textarea':
                    $value = implode("\n", $value);
                    break;
            }
		}

        return $value;
    }
}
