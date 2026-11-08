<a href="{{ $post->url() }}" class="card spot reveal group relative flex min-h-[340px] flex-col gap-5 overflow-hidden rounded-[30px] p-[clamp(22px,2.6vw,30px)] no-underline">
    <span aria-hidden="true" class="serif pointer-events-none absolute -top-3 right-5 text-[120px] leading-none text-[rgba(var(--w),0.06)]">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
    <span class="meta relative">{{ $post->dateLabel() }} · {{ __('site.notes.min', ['min' => $post->readingTime()]) }}</span>
    <span class="relative font-display text-[clamp(22px,2.2vw,27px)] leading-[1.15] font-semibold tracking-[-0.035em] text-balance text-c0">{{ $post->t('title') }}</span>
    <span class="relative line-clamp-3 text-c4">{{ $post->t('excerpt') }}</span>
    <span class="relative mt-auto flex items-center justify-between gap-3 border-t border-line pt-4">
        <span class="flex min-w-0 flex-wrap gap-1.5">
            @foreach (array_slice($post->tags ?? [], 0, 2) as $tag)<span class="chip">{{ $tag }}</span>@endforeach
        </span>
        <span aria-hidden="true" class="inline-flex h-10 w-10 flex-none items-center justify-center rounded-full border border-[rgba(var(--w),0.16)] text-c0 transition-all duration-500 group-hover:translate-x-1 group-hover:border-transparent group-hover:bg-accent group-hover:text-white"><x-ico name="arrow" /></span>
    </span>
</a>
