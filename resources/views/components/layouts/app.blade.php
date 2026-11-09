@props(['title' => null, 'description' => null, 'caseStudies' => [], 'image' => null, 'type' => 'website'])
@php
    $locale = app()->getLocale();
    $pageTitle = $title ? $title.' — '.config('site.name') : __('site.meta.title');
    $pageDescription = $description ?? __('site.meta.description');
    $ogImage = $image ?? \App\Support\OgImage::make('Full Stack · Laravel', __('site.meta.og_title'), config('site.name'), public_path('images/portrait.jpg'));
    $analytics = setting('analytics_provider');
    $bookingMode = booking_mode();
@endphp
<!doctype html>
<html lang="{{ $locale }}" class="theme-dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="theme-color" content="#141518">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="tr" href="{{ alternate_url('tr') }}">
    <link rel="alternate" hreflang="en" href="{{ alternate_url('en') }}">
    <link rel="alternate" hreflang="x-default" href="{{ alternate_url('tr') }}">

    <meta property="og:type" content="{{ $type }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="bektasozcetin.com">
    <meta property="og:locale" content="{{ $locale === 'en' ? 'en_US' : 'tr_TR' }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="{{ $ogImage }}">
    <link rel="alternate" type="application/rss+xml" title="bektasozcetin.com" href="{{ url('feed.xml') }}">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    {{-- Tema: sayfa çizilmeden önce uygulanır, yanıp sönme olmaz --}}
    <script>
        (function () {
            var t = null;
            try { t = localStorage.getItem('theme'); } catch (e) {}
            // Varsayılan koyu tema: işletim sistemi açık moddaysa bile koyu başlar.
            // Ziyaretçi düğmeyle seçim yaparsa tercihi localStorage'da saklanır.
            if (!t) t = 'dark';
            document.documentElement.className = 'theme-' + t;
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@1,400;1,500&family=IBM+Plex+Mono:wght@400;500;600&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Ziyaretçi istatistiği: Plausible çerez kullanmaz; GA4 yalnızca onaydan sonra app.js tarafından yüklenir --}}
    @if ($analytics === 'plausible' && setting('plausible_domain'))
        <script defer data-domain="{{ setting('plausible_domain') }}" src="{{ setting('plausible_src') ?: 'https://plausible.io/js/script.js' }}"></script>
    @endif

    {{ $head ?? '' }}

    <script type="application/ld+json">{!! \App\Support\Schema::person() !!}</script>
</head>
<body x-data="site" @keydown.window="globalKey($event)">
    <div class="relative overflow-clip">
        <div aria-hidden="true" class="blob pointer-events-none absolute -top-[260px] left-[28%] h-[900px] w-[900px] rounded-full bg-[radial-gradient(circle,rgba(var(--w),0.10),transparent_62%)]"></div>
        <div aria-hidden="true" class="blob pointer-events-none absolute top-[260px] left-[38%] h-[620px] w-[620px] rounded-full bg-[radial-gradient(circle,var(--vf),transparent_65%)]"></div>
        <div aria-hidden="true" class="pointer-events-none absolute inset-0 bg-[radial-gradient(rgba(var(--w),0.08)_1px,transparent_1px)] bg-size-[28px_28px] [mask-image:radial-gradient(ellipse_80%_30%_at_50%_0%,#000,transparent_75%)]"></div>

        <div class="relative mx-auto flex max-w-[1240px] flex-col gap-[clamp(96px,11vw,150px)] px-[clamp(16px,4vw,40px)] pt-[22px]">
            <a class="skip" href="#icerik">{{ __('site.skip') }}</a>

            @include('partials.overlays', ['caseStudies' => $caseStudies])
            @include('partials.header')

            <main id="icerik" class="flex flex-col gap-[clamp(96px,11vw,150px)]">
                {{ $slot }}
            </main>

            @include('partials.footer')
        </div>
    </div>

    @include('partials.floating')

    @if ($analytics === 'ga4' && setting('ga_id'))
        <div x-show="showConsent" x-cloak x-transition.opacity class="glass fixed bottom-4 left-4 z-30 flex max-w-[min(440px,calc(100vw-32px))] flex-col gap-3.5 rounded-[24px] p-5" role="dialog" aria-label="{{ __('site.consent.more') }}">
            <p class="m-0 text-sm leading-[1.6] text-c3">{{ __('site.consent.text') }} <a class="volt" href="{{ localized_route('privacy') }}">{{ __('site.consent.more') }}</a></p>
            <div class="flex gap-2">
                <button type="button" class="btn btn-primary btn-sm" @click="setConsent('granted')">{{ __('site.consent.accept') }}</button>
                <button type="button" class="btn glass btn-sm text-c1" @click="setConsent('denied')">{{ __('site.consent.decline') }}</button>
            </div>
        </div>
    @endif

    <script type="application/json" id="site-data">
        {!! json_encode([
            'locale' => $locale,
            'cases' => $caseStudies,
            'email' => config('site.email'),
            'viewUrl' => route('views.project', '__slug__'),
            'ga' => $analytics === 'ga4' ? setting('ga_id') : null,
            'booking' => [
                'mode' => $bookingMode,
                'url' => setting('booking_url'),
            ],
            'i18n' => [
                'linkCopied' => __('site.notes.link_copied'),
                'copied' => __('site.contact.copied'),
                'copyFail' => __('site.contact.copy_fail'),
                'toDark' => __('site.nav.to_dark'),
                'toLight' => __('site.nav.to_light'),
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
    </script>
</body>
</html>
