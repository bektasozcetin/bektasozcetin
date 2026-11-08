@if ($posts->isNotEmpty())
<section id="notlar" class="flex flex-col gap-11">
    <x-section-head :eyebrow="__('site.notes.eyebrow')" :a="__('site.notes.title_a')" :b="__('site.notes.title_b')" :lede="__('site.notes.lede')" serif>
        <x-slot:below>
            <p class="lede">{{ __('site.notes.lede') }}</p>
        </x-slot:below>
        <a class="btn glass text-c1" href="{{ localized_route('posts.index') }}">{{ __('site.notes.all') }} <x-ico name="arrow" :size="18" /></a>
    </x-section-head>

    <div class="grid grid-cols-[repeat(auto-fit,minmax(min(100%,320px),1fr))] gap-[18px]">
        @foreach ($posts as $i => $post)
            @include('posts.card', ['post' => $post, 'i' => $i])
        @endforeach
    </div>
</section>
@endif
