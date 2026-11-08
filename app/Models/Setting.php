<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

/**
 * Panelden düzenlenen anahtar/değer ayarları (CV dosyaları, görüşme takvimi, analitik).
 */
class Setting extends Model
{
    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected $casts = ['value' => 'json'];

    public const CACHE_KEY = 'site-settings';

    /** Varsayılan değerler: ayar hiç kaydedilmemişse bunlar kullanılır. */
    public const DEFAULTS = [
        'cv_tr' => null,
        'cv_en' => null,
        'booking_enabled' => true,
        'booking_url' => null,
        'booking_days' => ['1', '2', '3', '4', '5'],
        'booking_times' => ['10:00', '11:00', '13:30', '14:30', '15:30', '16:30'],
        'booking_duration' => 30,
        'booking_horizon' => 14,
        'booking_notice_hours' => 12,
        'github_username' => null,
        'analytics_provider' => 'none',
        'plausible_domain' => null,
        'plausible_src' => 'https://plausible.io/js/script.js',
        'ga_id' => null,
    ];

    public static function allValues(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            if (! Schema::hasTable('settings')) {
                return self::DEFAULTS;
            }

            return array_replace(self::DEFAULTS, static::query()->pluck('value', 'key')->all());
        });
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::allValues()[$key] ?? $default;
    }

    public static function put(array $values): void
    {
        foreach ($values as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        Cache::forget(self::CACHE_KEY);
    }
}
