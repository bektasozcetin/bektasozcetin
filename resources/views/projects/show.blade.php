<x-layouts.app :title="$case['title']" :description="$case['description']" :image="$ogImage" type="article">
    <div class="mt-[calc(clamp(20px,2.4vw,34px)-clamp(96px,11vw,150px))] flex flex-col gap-[18px]">
        <article class="card reveal relative rounded-[36px] p-3.5" x-data="{ shot: 0, gallery: @js($case['gallery']) }">
            @include('partials.case-body', ['modal' => false, 'case' => $case])
        </article>

        @if ($next)
            <a href="{{ $next['url'] }}" class="card spot reveal group flex items-center gap-5 rounded-[32px] p-3.5 pr-[clamp(16px,3vw,28px)] no-underline">
                <span aria-hidden="true" class="relative hidden h-[112px] w-[180px] flex-none overflow-hidden rounded-[22px] border border-line-soft bg-[#1F2024] sm:block">
                    @if ($next['image'])
                        <img src="{{ $next['image'] }}" alt="" loading="lazy" class="photo-grade thumb absolute inset-0 h-full w-full object-cover"><span class="photo-tint"></span>
                    @endif
                </span>
                <span class="flex min-w-0 flex-1 flex-col gap-1.5 pl-2 sm:pl-0">
                    <span class="lbl volt">{{ __('site.case.next') }}</span>
                    <span class="font-display text-[clamp(24px,3vw,34px)] leading-[1.05] font-semibold tracking-[-0.04em] text-c0">{{ $next['title'] }}</span>
                    <span class="text-sm text-c4">{{ $next['type'] }} · {{ $next['year'] }}</span>
                </span>
                <span aria-hidden="true" class="inline-flex h-12 w-12 flex-none items-center justify-center rounded-full bg-accent text-white transition-transform duration-500 group-hover:translate-x-1"><x-ico name="arrow" :size="18" /></span>
            </a>
        @endif

        <section aria-label="{{ __('site.band.label') }}" class="card reveal relative flex flex-wrap items-center justify-between gap-5 rounded-[32px] px-[26px] py-[22px]">
            <span class="flex min-w-0 flex-col">
                <span class="font-display text-xl font-semibold tracking-[-0.02em] text-c0">{{ __('site.band.title') }}</span>
                <span class="text-c4">{{ __('site.band.sub') }}</span>
            </span>
            <a class="btn btn-primary" href="{{ localized_route('home') }}#iletisim" @click="book($event)">{{ __('site.cta') }} <x-ico name="arrow" :size="18" /></a>
        </section>
    </div>
</x-layouts.app>
