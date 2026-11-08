<x-layouts.app :title="__('site.notes.eyebrow_plain')" :description="__('site.notes.index_lede')" :image="$ogImage">
    <section class="mt-[calc(clamp(20px,2.4vw,34px)-clamp(96px,11vw,150px))] flex flex-col gap-10">
        <div class="reveal flex flex-col gap-[18px] border-b border-line pb-10">
            <span class="eyebrow"><b></b>{{ __('site.notes.eyebrow_plain') }} · {{ __('site.notes.count', ['count' => $posts->count()]) }}</span>
            <h1 class="m-0 font-display text-[clamp(48px,8vw,112px)] leading-[0.92] font-extrabold tracking-[-0.055em] text-c0">
                {{ __('site.notes.title_a') }} <span class="serif grad-static text-[1.06em] font-normal tracking-[-0.02em]">{{ __('site.notes.title_b') }}</span>
            </h1>
            <p class="lede">{{ __('site.notes.index_lede') }}</p>
            @if ($tags->isNotEmpty())
                <nav aria-label="{{ __('site.notes.tag_label') }}" class="seg-group card mt-2 flex gap-1 self-start rounded-full p-[5px]">
                    <a class="seg-btn inline-flex items-center no-underline" href="{{ localized_route('posts.index') }}" aria-pressed="{{ $activeTag ? 'false' : 'true' }}">{{ __('site.notes.all_tags') }}</a>
                    @foreach ($tags as $tag)
                        <a class="seg-btn inline-flex items-center no-underline" href="{{ localized_route('posts.index', ['etiket' => $tag]) }}" aria-pressed="{{ $activeTag === $tag ? 'true' : 'false' }}">{{ $tag }}</a>
                    @endforeach
                </nav>
            @endif
        </div>

        @if ($posts->isEmpty())
            <p class="m-0 text-c4">{{ __('site.notes.empty') }}</p>
        @else
            <div class="flex flex-col">
                @foreach ($posts as $i => $post)
                    <a href="{{ $post->url() }}" class="note-row reveal group grid grid-cols-1 gap-x-10 gap-y-3 border-b border-line py-[clamp(24px,3vw,36px)] no-underline md:grid-cols-[180px_1fr_auto]">
                        <span class="flex flex-row gap-3 font-mono text-[13px] text-c5 md:flex-col md:gap-1.5 md:pt-2">
                            <span>{{ $post->dateLabel() }}</span>
                            <span>{{ __('site.notes.min', ['min' => $post->readingTime()]) }}</span>
                        </span>
                        <span class="flex min-w-0 flex-col gap-3">
                            <span class="font-display text-[clamp(26px,3.2vw,40px)] leading-[1.08] font-semibold tracking-[-0.04em] text-balance text-c0 transition-colors duration-300">{{ $post->t('title') }}</span>
                            <span class="max-w-[68ch] text-c4">{{ $post->t('excerpt') }}</span>
                            <span class="flex flex-wrap gap-1.5">@foreach ($post->tags ?? [] as $tag)<span class="chip">{{ $tag }}</span>@endforeach</span>
                        </span>
                        <span aria-hidden="true" class="hidden h-12 w-12 items-center justify-center self-center rounded-full border border-[rgba(var(--w),0.16)] text-c0 transition-all duration-500 group-hover:translate-x-1 group-hover:border-transparent group-hover:bg-accent group-hover:text-white md:inline-flex"><x-ico name="arrow" :size="18" /></span>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    <section aria-label="{{ __('site.band.label') }}" class="card reveal relative flex flex-wrap items-center justify-between gap-5 rounded-[32px] px-[26px] py-[22px]">
        <span class="flex min-w-0 flex-col">
            <span class="font-display text-xl font-semibold tracking-[-0.02em] text-c0">{{ __('site.notes.cta_title') }}</span>
            <span class="text-c4">{{ __('site.notes.cta_sub') }}</span>
        </span>
        <a class="btn btn-primary" href="{{ localized_route('home') }}#iletisim" @click="book($event)">{{ __('site.cta') }} <x-ico name="arrow" :size="18" /></a>
    </section>
</x-layouts.app>
