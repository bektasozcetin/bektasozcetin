{{--
    Vaka çalışması gövdesi.
    $modal = true  → Alpine ile "current" nesnesinden doldurulur (açılır pencere).
    $modal = false → $case dizisinden sunucuda çizilir (ayrı proje sayfası, SEO için).
--}}
@php
    $m = $modal ?? false;
    // Sunucu tarafında metni basar, modalda Alpine bağlaması üretir.
    $txt = fn (string $key) => $m ? 'x-text="current.'.$key.'"' : '';
    $val = fn (string $key) => $m ? '' : (string) ($case[$key] ?? '');
@endphp

<div aria-hidden="true" class="photo-wrap relative aspect-[16/10] overflow-hidden rounded-[26px] border border-line-soft bg-[linear-gradient(135deg,#3A3B40,#1C1D20)] sm:aspect-auto sm:h-[440px]">
    <div class="absolute -top-20 -right-20 h-80 w-80 rounded-full bg-[radial-gradient(circle,var(--vg),transparent_65%)]"></div>
    @if ($m)
        <template x-if="current.image"><div><img :src="current.gallery[shot] || current.image" alt="" class="photo-grade absolute inset-0 h-full w-full object-cover object-top"><span class="photo-tint"></span></div></template>
    @elseif (! empty($case['image']))
        <img src="{{ $case['image'] }}" :src="gallery[shot]" alt="" class="photo-grade absolute inset-0 h-full w-full object-cover object-top"><span class="photo-tint"></span>
    @endif
    <div class="glass absolute top-9 right-10 -bottom-[30px] left-10 flex flex-col rounded-[18px] bg-[rgba(var(--k),0.6)]" @if ($m) x-show="!current.image" @elseif (! empty($case['image'])) hidden @endif>
        <div class="flex gap-1.5 border-b border-line-soft px-3.5 py-3">
            <span class="h-2 w-2 rounded-full bg-[rgba(var(--w),0.35)]"></span><span class="h-2 w-2 rounded-full bg-[rgba(var(--w),0.22)]"></span><span class="h-2 w-2 rounded-full bg-[rgba(var(--w),0.12)]"></span>
        </div>
        <div class="flex flex-1 items-center justify-center font-mono text-[13px] text-c5">{{ __('site.case.shot') }}</div>
    </div>
</div>

{{-- Galeri: küçük resme tıklayınca üstteki büyük görsel değişir --}}
@if ($m)
    <div class="flex flex-col gap-2 px-[clamp(14px,3vw,30px)] pt-5" x-show="current.gallery.length > 1">
        <span class="lbl">{{ __('site.case.gallery') }}</span>
        <div class="no-scrollbar flex gap-2.5 overflow-x-auto pb-1">
            <template x-for="(src, n) in current.gallery" :key="src">
                <button type="button" class="shot-btn" @click="shot = n" :aria-pressed="(shot === n).toString()" :aria-label="@js(__('site.case.show_shot')).replace(':n', n + 1)">
                    <img :src="src" alt="" loading="lazy">
                </button>
            </template>
        </div>
    </div>
@elseif (count($case['gallery']) > 1)
    <div class="flex flex-col gap-2 px-[clamp(14px,3vw,30px)] pt-5">
        <span class="lbl">{{ __('site.case.gallery') }}</span>
        <div class="no-scrollbar flex gap-2.5 overflow-x-auto pb-1">
            @foreach ($case['gallery'] as $n => $src)
                <button type="button" class="shot-btn" @click="shot = {{ $n }}" :aria-pressed="(shot === {{ $n }}).toString()" aria-label="{{ __('site.case.show_shot', ['n' => $n + 1]) }}">
                    <img src="{{ $src }}" alt="" loading="lazy">
                </button>
            @endforeach
        </div>
    </div>
@endif

@if ($m)
    <button type="button" @click="closeCase" aria-label="{{ __('site.case.close') }}" class="glass absolute top-[26px] right-[26px] inline-flex h-11 w-11 cursor-pointer items-center justify-center rounded-full text-c0">
        <x-ico name="close" :stroke="2.2" />
    </button>
@endif

<div class="flex flex-col gap-7 px-[clamp(14px,3vw,30px)] pt-7 pb-[22px]">
    <div class="flex flex-col gap-2.5">
        <span class="eyebrow items-start"><b class="mt-[7px] flex-none"></b><span>{{ __('site.case.label') }} · <span {!! $txt('type') !!}>{{ $val('type') }}</span> · <span {!! $txt('year') !!}>{{ $val('year') }}</span></span></span>
        <h1 id="case-title" class="m-0 font-display text-[clamp(30px,4vw,44px)] leading-[1.05] font-semibold tracking-[-0.04em] text-c0" {!! $txt('title') !!}>{{ $val('title') }}</h1>
        <p class="m-0 max-w-[60ch] text-[17px] text-c3" {!! $txt('description') !!}>{{ $val('description') }}</p>
    </div>

    <dl class="m-0 grid grid-cols-[repeat(auto-fit,minmax(min(100%,180px),1fr))] gap-3">
        @foreach (['role' => 'role', 'duration' => 'duration', 'stack' => 'stack'] as $key => $label)
            <div class="glass-dark rounded-2xl px-4 py-3.5"><dt class="lbl">{{ __('site.case.'.$label) }}</dt><dd class="mt-1 mb-0 text-c0" {!! $txt($key) !!}>{{ $val($key) }}</dd></div>
        @endforeach
    </dl>

    <div class="grid grid-cols-[repeat(auto-fit,minmax(min(100%,320px),1fr))] gap-7">
        <div class="flex min-w-0 flex-col gap-[22px]">
            @foreach (['problem', 'approach', 'lesson'] as $key)
                <div class="flex flex-col gap-2" @if ($m) x-show="current.{{ $key }}" @elseif (empty($case[$key])) hidden @endif>
                    <h2 class="h3 text-lg">{{ __('site.case.'.$key) }}</h2>
                    <p class="m-0 text-c4" {!! $txt($key) !!}>{{ $val($key) }}</p>
                </div>
            @endforeach
        </div>
        <div class="flex min-w-0 flex-col gap-4">
            <h2 class="h3 text-lg">{{ __('site.case.decisions') }}</h2>
            @if ($m)
                <template x-for="d in current.decisions" :key="d.q">
                    <div class="glass-dark flex flex-col gap-1.5 rounded-2xl p-4">
                        <span class="text-[15px] font-semibold text-c0" x-text="d.q"></span>
                        <span class="text-sm text-c4" x-text="d.a"></span>
                    </div>
                </template>
            @else
                @foreach ($case['decisions'] as $d)
                    <div class="glass-dark flex flex-col gap-1.5 rounded-2xl p-4">
                        <span class="text-[15px] font-semibold text-c0">{{ $d['q'] }}</span>
                        <span class="text-sm text-c4">{{ $d['a'] }}</span>
                    </div>
                @endforeach
            @endif
            <div class="card relative flex flex-col gap-1 rounded-[22px] p-[18px]">
                <span class="lbl volt">{{ __('site.projects.result') }}</span>
                <span class="serif text-[26px] leading-[1.2] text-c0" {!! $txt('result') !!}>{{ $val('result') }}</span>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-3.5 border-t border-line-soft pt-[18px]">
        <div class="flex flex-wrap gap-1.5">
            @if ($m)
                <template x-for="tag in current.tags" :key="tag"><span class="chip" x-text="tag"></span></template>
            @else
                @foreach ($case['tags'] as $tag)<span class="chip">{{ $tag }}</span>@endforeach
            @endif
        </div>
        <div class="flex flex-wrap gap-2">
            @if ($m)
                <button type="button" class="btn glass btn-sm cursor-pointer text-c1" @click="nextCase" x-show="Object.keys(cases).length > 1">{{ __('site.case.next_short') }}</button>
                <a class="btn glass btn-sm text-c1" :href="current.url">{{ __('site.projects.full_page') }}</a>
                <a class="btn btn-primary btn-sm" :href="current.live_url" x-show="current.live_url" target="_blank" rel="noopener">{{ __('site.projects.live_demo') }}</a>
            @else
                <a class="btn glass btn-sm text-c1" href="{{ localized_route('home') }}#projeler">{{ __('site.case.back') }}</a>
                @if ($case['live_url'])
                    <a class="btn btn-primary btn-sm" href="{{ $case['live_url'] }}" target="_blank" rel="noopener">{{ __('site.projects.live_demo') }}</a>
                @endif
            @endif
        </div>
    </div>
</div>
