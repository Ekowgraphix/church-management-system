<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'key',
        'value',
        'type',
        'description',
        'is_encrypted',
    ];

    protected $casts = [
        'is_encrypted' => 'boolean',
    ];

    /**
     * Get setting value with caching
     */
    public static function get($key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }

            return $setting->getValue();
        });
    }

    /**
     * Set setting value
     */
    public static function set($key, $value, $category = 'general', $type = 'text', $encrypted = false)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'category' => $category,
                'value' => $encrypted ? Crypt::encryptString($value) : $value,
                'type' => $type,
                'is_encrypted' => $encrypted,
            ]
        );

        Cache::forget("setting_{$key}");

        return $setting;
    }

    /**
     * Get decrypted value
     */
    public function getValue()
    {
        if ($this->is_encrypted && $this->value) {
            try {
                return Crypt::decryptString($this->value);
            } catch (\Exception $e) {
                return null;
            }
        }

        // Cast based on type
        return match($this->type) {
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'number', 'integer' => (int) $this->value,
            'json', 'array' => json_decode($this->value, true),
            default => $this->value,
        };
    }

    /**
     * Get all settings by category
     */
    public static function getByCategory($category)
    {
        return Cache::remember("settings_category_{$category}", 3600, function () use ($category) {
            return self::where('category', $category)->get()->mapWithKeys(function ($setting) {
                return [$setting->key => $setting->getValue()];
            });
        });
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        Cache::flush(); // Or use a more targeted approach with tags
    }
}
