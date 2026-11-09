{{-- Alt sayfalara geçiş: ana sayfa kısa kalsın, derin içerik kendi adresinde dursun. --}}
<section id="kesfet" class="flex flex-col gap-11">
    <x-section-head :eyebrow="__('site.explore.eyebrow')" :a="__('site.explore.title_a')" :b="__('site.explore.title_b')" :lede="__('site.explore.lede')" />

    <div class="grid grid-cols-[repeat(auto-fit,minmax(min(100%,290px),1fr))] gap-[18px]">
        @foreach (config('nav.pages') as $name => $cfg)
            <a href="{{ localized_route($name) }}" class="card spot reveal group flex flex-col gap-3 rounded-[26px] p-6 no-underline">
                <div class="flex items-start justify-between gap-3">
                    <span class="lbl volt">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <x-ico name="arrow" :size="18" class="-rotate-45 text-c4 transition-transform duration-300 group-hover:translate-x-1 group-hover:text-c0" />
                </div>
                <h3 class="m-0 font-display text-[22px] font-semibold tracking-[-0.02em] text-c0">{{ __('site.nav.'.$cfg['key']) }}</h3>
                <p class="m-0 text-[15px] leading-[1.65] text-c4">{{ __('site.pages.'.$name.'.lede') }}</p>
            </a>
        @endforeach
    </div>
</section>
