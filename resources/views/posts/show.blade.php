@php
    $url = $post->url();
    $title = $post->t('title');
@endphp
<x-layouts.app :title="$title" :description="$post->t('excerpt')" :image="$ogImage" type="article">
    <x-slot:head>
        <meta property="article:published_time" content="{{ $post->published_at->toIso8601String() }}">
        <script type="application/ld+json">
            {!! \App\Support\Schema::blogPosting($post, $title, $url, $ogImage) !!}
        </script>
    </x-slot:head>

    <div class="mt-[calc(clamp(20px,2.4vw,34px)-clamp(96px,11vw,150px))] flex flex-col gap-[18px]">
        <article class="card reveal relative flex flex-col gap-10 rounded-[36px] px-[clamp(20px,6vw,88px)] py-[clamp(28px,5vw,72px)]">
            <header class="flex flex-col gap-6">
                <a class="navlink self-start px-0 font-mono text-[13px]" href="{{ localized_route('posts.index') }}">{{ __('site.notes.back') }}</a>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                    <span class="meta">{{ $post->dateLabel() }} · {{ __('site.notes.min', ['min' => $post->readingTime()]) }}</span>
                    <span class="flex flex-wrap gap-1.5">@foreach ($post->tags ?? [] as $tag)<a class="chip no-underline hover:text-c0" href="{{ localized_route('posts.index', ['etiket' => $tag]) }}">{{ $tag }}</a>@endforeach</span>
                </div>
                <h1 class="m-0 max-w-[20ch] font-display text-[clamp(38px,5.6vw,72px)] leading-[1.0] font-extrabold tracking-[-0.05em] text-balance text-c0">{{ $title }}</h1>
                <p class="serif m-0 max-w-[46ch] text-[clamp(21px,2.2vw,26px)] leading-[1.45] text-c2">{{ $post->t('excerpt') }}</p>
                <div class="flex flex-wrap items-center justify-between gap-4 border-y border-line py-4">
                    <span class="flex items-center gap-3">
                        <img src="{{ asset('images/portrait.jpg') }}" alt="" width="44" height="44" class="h-11 w-11 rounded-full object-cover object-[50%_28%]">
                        <span class="flex flex-col leading-[1.3]">
                            <span class="font-display font-semibold text-c0">{{ config('site.name') }}</span>
                            <span class="text-[13px] text-c4">{{ __('site.notes.author_role') }}</span>
                        </span>
                    </span>
                    @include('posts.share', ['url' => $url, 'title' => $title])
                </div>
            </header>

            @if ($post->coverUrl())
                <figure class="photo-wrap relative m-0 aspect-[16/9] overflow-hidden rounded-[26px] border border-line-soft bg-[#1F2024]">
                    <img src="{{ $post->coverUrl() }}" alt="" class="photo-grade h-full w-full object-cover"><span class="photo-tint"></span>
                </figure>
            @endif

            <div class="prose-note mx-auto w-full max-w-[70ch]">{!! $post->html() !!}</div>

            <footer class="mx-auto flex w-full max-w-[70ch] flex-wrap items-center justify-between gap-4 border-t border-line pt-6">
                <span class="font-mono text-[13px] text-c5">{{ __('site.notes.share') }}</span>
                @include('posts.share', ['url' => $url, 'title' => $title])
            </footer>
        </article>

        @if ($newer || $older)
            <nav class="grid grid-cols-[repeat(auto-fit,minmax(min(100%,320px),1fr))] gap-[18px]" aria-label="{{ __('site.notes.eyebrow_plain') }}">
                @foreach (['older' => $older, 'newer' => $newer] as $dir => $p)
                    @if ($p)
                        <a href="{{ $p->url() }}" class="card spot reveal group flex flex-col gap-2 rounded-[28px] p-6 no-underline {{ $dir === 'newer' ? 'sm:items-end sm:text-right' : '' }}">
                            <span class="lbl volt">{{ $dir === 'newer' ? __('site.notes.newer').' →' : '← '.__('site.notes.older') }}</span>
                            <span class="font-display text-[22px] leading-[1.2] font-semibold tracking-[-0.03em] text-c0">{{ $p->t('title') }}</span>
                        </a>
                    @endif
                @endforeach
            </nav>
        @endif

        <section aria-label="{{ __('site.band.label') }}" class="card reveal relative flex flex-wrap items-center justify-between gap-5 rounded-[32px] px-[26px] py-[22px]">
            <span class="flex min-w-0 flex-col">
                <span class="font-display text-xl font-semibold tracking-[-0.02em] text-c0">{{ __('site.notes.cta_title') }}</span>
                <span class="text-c4">{{ __('site.notes.cta_sub') }}</span>
            </span>
            <a class="btn btn-primary" href="{{ localized_route('home') }}#iletisim" @click="book($event)">{{ __('site.cta') }} <x-ico name="arrow" :size="18" /></a>
        </section>
    </div>
</x-layouts.app>
