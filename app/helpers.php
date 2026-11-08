<?php

use App\Models\Setting;

/*
| Çok dilli route yardımcıları.
| Türkçe route'lar önneksiz (home), İngilizceler "en." önekli (en.home).
*/

if (! function_exists('localized_route')) {
    function localized_route(string $name, mixed $parameters = [], ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return route(($locale === 'en' ? 'en.' : '').$name, $parameters);
    }
}

if (! function_exists('alternate_url')) {
    /** Açık olan sayfanın diğer dildeki adresi (dil düğmesi ve hreflang için). */
    function alternate_url(string $locale): string
    {
        $route = request()->route();
        $name = $route?->getName();

        if (! $name) {
            return url($locale === 'en' ? '/en' : '/');
        }

        return localized_route(preg_replace('/^en\./', '', $name), $route->parameters(), $locale);
    }
}

if (! function_exists('setting')) {
    /** Panelden düzenlenen site ayarı. */
    function setting(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('cv_url')) {
    /** Aktif dildeki CV dosyasının adresi; o dilde yoksa Türkçesi. */
    function cv_url(): ?string
    {
        $file = setting('cv_'.app()->getLocale()) ?: setting('cv_tr');

        return $file ? asset('storage/'.$file) : (config('site.cv_url') ?: null);
    }
}

if (! function_exists('booking_mode')) {
    /** "internal" (site içi takvim), "external" (Cal.com vb. bağlantı) ya da null. */
    function booking_mode(): ?string
    {
        if (filled(setting('booking_url'))) {
            return 'external';
        }

        return setting('booking_enabled') ? 'internal' : null;
    }
}
