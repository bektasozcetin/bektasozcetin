@php
    $home = localized_route('home');
    // Mobil menü: ana sayfa çapaları + config/nav.php'deki alt sayfalar.
    $menuItems = collect([['url' => $home.'#projeler', 'label' => __('site.nav.work')]])
        ->concat(collect(config('nav.pages'))->map(fn ($cfg, $name) => [
            'url' => localized_route($name),
            'label' => __('site.nav.'.$cfg['key']),
        ])->values())
        ->push(['url' => localized_route('posts.index'), 'label' => __('site.nav.notes')])
        ->push(['url' => $home.'#iletisim', 'label' => __('site.nav.contact')])
        ->values();
    $otherLocale = app()->getLocale() === 'en' ? 'tr' : 'en';
    // Bölüm kimliği → hangi alt sayfada yaşıyor. Ana sayfada kalanlar (projeler,
    // notlar, iletişim) haritada yok; onlar çapa olarak çalışmaya devam ediyor.
    $sectionPage = [];
    foreach (config('nav.pages') as $pageName => $pageCfg) {
        foreach ($pageCfg['sections'] as $sectionId) {
            $sectionPage[$sectionId] = $pageName;
        }
    }

    $paletteItems = collect(__('site.palette.sections'))
        ->map(fn ($s) => isset($sectionPage[$s[0]])
            ? ['type' => 'link', 'target' => localized_route($sectionPage[$s[0]]), 'label' => $s[1], 'hint' => $s[2], 'icon' => $s[3], 'kind' => __('site.palette.section')]
            : ['type' => 'section', 'target' => $s[0], 'label' => $s[1], 'hint' => $s[2], 'icon' => $s[3], 'kind' => __('site.palette.section')])
        ->push(booking_mode()
            ? ['type' => 'book', 'label' => __('site.cta'), 'hint' => __('site.palette.book'), 'icon' => '→', 'kind' => __('site.palette.action'), 'act' => true]
            : ['type' => 'section', 'target' => 'iletisim', 'label' => __('site.cta'), 'hint' => __('site.palette.to_contact'), 'icon' => '→', 'kind' => __('site.palette.action'), 'act' => true])
        ->push(['type' => 'link', 'target' => localized_route('posts.index'), 'label' => __('site.palette.notes_page'), 'hint' => __('site.notes.og_title'), 'icon' => 'N', 'kind' => __('site.palette.action'), 'act' => true])
        ->when(cv_url(), fn ($c, $cv) => $c->push(['type' => 'link', 'target' => $cv, 'label' => __('site.palette.cv'), 'hint' => 'PDF', 'icon' => '↓', 'kind' => __('site.palette.action'), 'act' => true]))
        ->push(['type' => 'copy', 'label' => __('site.contact.copy'), 'hint' => config('site.email'), 'icon' => '⧉', 'kind' => __('site.palette.action'), 'act' => true])
        ->push(['type' => 'link', 'target' => alternate_url($otherLocale), 'label' => __('site.palette.switch_lang'), 'hint' => strtoupper($otherLocale), 'icon' => 'A', 'kind' => __('site.palette.action'), 'act' => true])
        ->push(['type' => 'theme', 'label' => '', 'hint' => '', 'icon' => '◐', 'kind' => __('site.palette.action'), 'act' => true])
        ->values();
@endphp

{{-- Bildirim --}}
<div x-show="toast" x-transition.opacity.duration.300ms x-cloak class="glass toast flex items-center gap-2.5 rounded-full px-5 py-3 text-sm font-medium text-c0" role="status">
    <x-ico name="check" :stroke="2.6" class="volt" /><span x-text="toast"></span>
</div>

{{-- Mobil menü --}}
<template x-teleport="body">
    <div x-show="menu" x-cloak x-transition.opacity class="overlay" @click.self="menu = false">
        <div class="glass panel-in flex w-full max-w-[520px] flex-col gap-[18px] rounded-[32px] p-[22px]" role="dialog" aria-modal="true" aria-label="{{ __('site.nav.menu_label') }}" x-trap.noscroll="menu">
            <div class="flex items-center justify-between gap-3">
                <span class="meta">{{ __('site.cover.issue') }}</span>
                <button type="button" class="glass inline-flex h-11 w-11 cursor-pointer items-center justify-center rounded-full text-c0" @click="menu = false" aria-label="{{ __('site.nav.close_menu') }}">
                    <x-ico name="close" :stroke="2.2" />
                </button>
            </div>
            <nav class="flex flex-col" aria-label="{{ __('site.nav.menu_label') }}">
                @foreach ($menuItems as $i => $item)
                    <a class="menu-link" href="{{ $item['url'] }}" @click="menu = false">
                        <span class="volt w-6 font-mono text-xs font-medium">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="serif text-[32px] leading-[1.1] text-c0">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
            <div class="flex flex-wrap items-center justify-between gap-3">
                @include('partials.lang-switch', ['class' => 'glass-dark p-1'])
                <button type="button" class="navlink cursor-pointer border border-line bg-fill-soft" @click="toggleTheme" x-text="themeLabel"></button>
                <a class="btn btn-primary" href="{{ $home }}#iletisim" @click="menu = false; book($event)">{{ __('site.cta') }}</a>
            </div>
        </div>
    </div>
</template>

{{-- Komut paleti (Ctrl/⌘ + K) --}}
<template x-teleport="body">
    <div x-show="palette" x-cloak x-transition.opacity class="overlay" @click.self="closePalette" x-data="palette(@js($paletteItems))" @keydown="onKey($event)">
        <div class="glass panel-in mt-[6vh] w-full max-w-[620px] overflow-hidden rounded-[28px]" role="dialog" aria-modal="true" aria-label="{{ __('site.nav.quick') }}" x-trap.noscroll="palette">
            <div class="flex items-center gap-3 border-b border-line-soft px-5 py-4">
                <x-ico name="search" :size="18" class="volt" />
                <label for="pal-q" class="sr-only">{{ __('site.palette.search') }}</label>
                <input id="pal-q" type="text" x-model="q" @input="i = 0" x-ref="input" placeholder="{{ __('site.palette.search') }}" aria-describedby="pal-help" autocomplete="off"
                       class="min-h-11 min-w-0 flex-1 border-0 bg-transparent text-[17px] text-c0 outline-none placeholder:text-c5">
                <span class="kbd">esc</span>
            </div>
            <div class="flex max-h-[56vh] flex-col gap-1 overflow-y-auto p-2.5">
                <template x-for="(it, idx) in results" :key="idx">
                    <button type="button" @click="run(it)" @mouseenter="i = idx"
                            class="flex min-h-14 w-full cursor-pointer items-center gap-3 rounded-2xl border px-3 py-2 text-left"
                            :class="idx === i ? 'bg-[rgba(var(--w),0.10)] border-accent' : 'border-transparent'">
                        <span class="inline-flex h-[34px] w-[34px] flex-none items-center justify-center rounded-[10px] font-mono text-[13px] font-semibold text-c0"
                              :class="(idx === i || it.act) ? 'bg-accent' : 'bg-line-soft'" x-text="it.icon"></span>
                        <span class="flex min-w-0 flex-1 flex-col leading-[1.35]">
                            <span class="text-[15px] font-medium text-c0" x-text="label(it)"></span>
                            <span class="font-mono text-xs text-c5" x-text="hint(it)"></span>
                        </span>
                        <span class="font-mono text-[11px] text-c5" x-text="it.kind"></span>
                    </button>
                </template>
                <p x-show="!results.length" class="m-0 px-3.5 py-[18px] text-c4" x-text="@js(__('site.palette.empty')).replace(':q', q)"></p>
            </div>
            <div id="pal-help" class="flex flex-wrap gap-3.5 border-t border-line-soft px-5 py-3 font-mono text-xs text-c5">
                <span><span class="kbd">↑</span> <span class="kbd">↓</span> {{ __('site.palette.move') }}</span>
                <span><span class="kbd">enter</span> {{ __('site.palette.select') }}</span>
                <span><span class="kbd">esc</span> {{ __('site.palette.close') }}</span>
            </div>
        </div>
    </div>
</template>

{{-- Vaka çalışması --}}
@if (! empty($caseStudies))
    <template x-teleport="body">
        <div x-show="caseSlug" x-cloak x-transition.opacity class="overlay" @click.self="closeCase">
            <template x-if="current">
                <article class="glass panel-in relative mb-[5vh] w-full max-w-[920px] rounded-[36px] p-3.5" role="dialog" aria-modal="true" aria-labelledby="case-title" x-trap.noscroll="caseSlug" x-data="{ shot: 0 }" x-effect="caseSlug; shot = 0">
                    @include('partials.case-body', ['modal' => true])
                </article>
            </template>
        </div>
    </template>
@endif

{{-- Görüşme takvimi --}}
@if (booking_mode() === 'internal')
    @php
        $bookingCfg = [
            'slotsUrl' => route('meetings.slots'),
            'storeUrl' => localized_route('meetings.store'),
            'locale' => app()->getLocale(),
            'title' => __('site.booking.ics_title'),
            'errors' => __('site.contact.errors') + __('site.booking.errors'),
            'error' => __('site.contact.error'),
        ];
    @endphp
    <template x-teleport="body">
        <div x-show="booking" x-cloak x-transition.opacity class="overlay" @click.self="closeBooking">
            <div class="glass panel-in relative mb-[5vh] flex w-full max-w-[760px] flex-col gap-6 rounded-[36px] p-[clamp(18px,4vw,36px)]" role="dialog" aria-modal="true" aria-labelledby="book-title"
                 x-trap.noscroll="booking" x-data="bookingForm(@js($bookingCfg))">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex min-w-0 flex-col gap-3">
                        <span class="eyebrow"><b></b>{{ __('site.booking.label') }}</span>
                        <h2 id="book-title" class="m-0 font-display text-[clamp(30px,4.4vw,44px)] leading-none font-bold tracking-[-0.045em] text-c0">{{ __('site.booking.title') }}</h2>
                        <p class="m-0 text-c4" x-text="@js(__('site.booking.sub')).replace(':min', duration)">{{ __('site.booking.sub', ['min' => setting('booking_duration')]) }}</p>
                    </div>
                    <button type="button" @click="closeBooking" aria-label="{{ __('site.booking.close') }}" class="glass inline-flex h-11 w-11 flex-none cursor-pointer items-center justify-center rounded-full text-c0">
                        <x-ico name="close" :stroke="2.2" />
                    </button>
                </div>

                {{-- Tamamlandı --}}
                <div x-show="done" x-cloak role="status" class="flex flex-col items-center gap-4 py-8 text-center">
                    <span class="pop inline-flex h-[84px] w-[84px] items-center justify-center rounded-full bg-accent text-white shadow-[0_0_60px_-6px_var(--vg)]"><x-ico name="check" :size="38" :stroke="2.6" /></span>
                    <span class="font-display text-[26px] font-semibold tracking-[-0.03em] text-c0">{{ __('site.booking.done_title') }}</span>
                    <span class="max-w-[40ch] text-c4" x-text="@js(__('site.booking.done_sub')).replace(':date', whenLabel)"></span>
                    <div class="flex flex-wrap justify-center gap-2">
                        <a class="btn glass text-c1" :href="icsHref" download="gorusme.ics">{{ __('site.booking.add_cal') }}</a>
                        <button type="button" class="btn btn-primary cursor-pointer" @click="closeBooking">{{ __('site.booking.close') }}</button>
                    </div>
                </div>

                <div x-show="!done" class="flex flex-col gap-6">
                    <p x-show="loading" class="m-0 font-mono text-sm text-c4">{{ __('site.booking.loading') }}</p>
                    <p x-show="loadError" x-cloak class="err m-0" role="alert">{{ __('site.booking.load_error') }}</p>
                    <p x-show="!loading && !loadError && !days.length" x-cloak class="m-0 text-c4">{{ __('site.booking.no_slots') }}</p>

                    <template x-if="days.length">
                        <div class="flex flex-col gap-6">
                            {{-- 1. Gün --}}
                            <div class="flex flex-col gap-3">
                                <div class="flex flex-wrap items-baseline justify-between gap-2">
                                    <span class="lbl" id="b-day"><span class="volt">01</span> · {{ __('site.booking.step_day') }}</span>
                                    <span class="font-mono text-xs text-c5">{{ __('site.booking.tz') }}</span>
                                </div>
                                <div role="group" aria-labelledby="b-day" class="no-scrollbar -mx-1 flex gap-2 overflow-x-auto px-1 pb-1.5">
                                    <template x-for="d in days" :key="d.date">
                                        <button type="button" class="day-btn" @click="pickDay(d.date)" :aria-pressed="(day === d.date).toString()" :disabled="!freeCount(d)">
                                            <span class="text-[11px] tracking-[.08em] uppercase" x-text="fmt(d.date, { weekday: 'short' })"></span>
                                            <span class="font-display text-[26px] leading-none font-bold tracking-[-0.04em]" x-text="fmt(d.date, { day: 'numeric' })"></span>
                                            <span class="text-[11px]" x-text="fmt(d.date, { month: 'short' })"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            {{-- 2. Saat --}}
                            <div class="flex flex-col gap-3" x-show="day">
                                <span class="lbl" id="b-time"><span class="volt">02</span> · {{ __('site.booking.step_time') }}</span>
                                <div role="group" aria-labelledby="b-time" class="grid grid-cols-[repeat(auto-fill,minmax(92px,1fr))] gap-2">
                                    <template x-for="[t, free] in currentTimes" :key="t">
                                        <button type="button" class="pill justify-center tabular-nums" @click="time = t; errors.slot = null" :aria-pressed="(time === t).toString()" :disabled="!free"
                                                :aria-label="free ? t : t + ' · {{ __('site.booking.full') }}'" x-text="t"></button>
                                    </template>
                                </div>
                                <span class="err" x-show="errors.slot" x-text="errors.slot" x-cloak role="alert"></span>
                            </div>

                            {{-- 3. Bilgiler --}}
                            <form class="flex flex-col gap-4 border-t border-line-soft pt-6" x-show="time" x-collapse novalidate @submit.prevent="submit">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <span class="lbl"><span class="volt">03</span> · {{ __('site.booking.step_info') }}</span>
                                    <span class="chip inline-flex items-center gap-2"><span class="vdot live h-1.5 w-1.5"></span><span x-text="whenLabel"></span></span>
                                </div>
                                <div class="hp" aria-hidden="true"><label>Website <input type="text" tabindex="-1" autocomplete="off" x-model="website"></label></div>
                                <div class="grid grid-cols-[repeat(auto-fit,minmax(min(100%,200px),1fr))] gap-3.5">
                                    <div class="flex flex-col gap-2">
                                        <label class="lbl" for="b-name">{{ __('site.contact.name') }}</label>
                                        <input id="b-name" class="field" type="text" autocomplete="name" placeholder="{{ __('site.contact.name_ph') }}" x-model="name" maxlength="120" :aria-invalid="(!!errors.name).toString()" aria-describedby="be-name">
                                        <span id="be-name" class="err" x-show="errors.name" x-text="errors.name" x-cloak></span>
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label class="lbl" for="b-mail">{{ __('site.contact.email') }}</label>
                                        <input id="b-mail" class="field" type="email" autocomplete="email" inputmode="email" placeholder="{{ __('site.contact.mail_ph') }}" x-model="email" maxlength="190" :aria-invalid="(!!errors.email).toString()" aria-describedby="be-email">
                                        <span id="be-email" class="err" x-show="errors.email" x-text="errors.email" x-cloak></span>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-2.5">
                                    <span class="lbl" id="b-topic">{{ __('site.booking.topic') }}</span>
                                    <div role="group" aria-labelledby="b-topic" class="flex flex-wrap gap-2">
                                        @foreach (__('site.contact.types') as $key => $label)
                                            <button type="button" class="pill" @click="topic = '{{ $key }}'" :aria-pressed="(topic === '{{ $key }}').toString()">{{ $label }}</button>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label class="lbl" for="b-note">{{ __('site.booking.note') }}</label>
                                    <textarea id="b-note" class="field min-h-[96px] resize-y" rows="3" placeholder="{{ __('site.booking.note_ph') }}" x-model="note" maxlength="2000"></textarea>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label for="b-kvkk" class="flex min-h-11 cursor-pointer items-start gap-3 text-sm leading-[1.55] text-c3">
                                        <input id="b-kvkk" type="checkbox" x-model="kvkk" class="m-0 h-[22px] w-[22px] flex-none accent-(--v)" :aria-invalid="(!!errors.kvkk).toString()" aria-describedby="be-kvkk">
                                        <span>{{ __('site.contact.kvkk_a') }} <a href="{{ localized_route('privacy') }}" class="volt" target="_blank">{{ __('site.contact.kvkk_link') }}</a> {{ __('site.contact.kvkk_b') }}</span>
                                    </label>
                                    <span id="be-kvkk" class="err" x-show="errors.kvkk" x-text="errors.kvkk" x-cloak></span>
                                </div>
                                <p class="err m-0" x-show="errors.general" x-text="errors.general" x-cloak role="alert"></p>
                                <button type="submit" class="btn btn-primary justify-center" :aria-busy="busy.toString()" :disabled="busy">
                                    <span x-text="busy ? @js(__('site.booking.sending')) : @js(__('site.booking.submit'))">{{ __('site.booking.submit') }}</span>
                                    <x-ico name="calendar" :size="18" />
                                </button>
                            </form>
                        </div>
                    </template>

                    <a class="navlink self-start px-0 text-sm" href="{{ localized_route('home') }}#iletisim" @click="closeBooking()">{{ __('site.booking.or_form') }}</a>
                </div>
            </div>
        </div>
    </template>
@endif
