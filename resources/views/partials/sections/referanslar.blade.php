@if ($testimonials->isNotEmpty())
    <section id="referanslar" class="flex flex-col gap-12">
        <x-section-head :level="$headLevel ?? 'h2'" stack :eyebrow="__('site.refs.eyebrow')" :a="__('site.refs.title_a')" :b="__('site.refs.title_b')" />
        <div class="grid grid-cols-[repeat(auto-fit,minmax(min(100%,300px),1fr))] gap-[18px]">
            @foreach ($testimonials as $ref)
                <figure class="card spot reveal m-0 flex flex-col gap-5 rounded-[32px] p-[30px]">
                    <span class="quote -mb-2.5 h-10" aria-hidden="true">“</span>
                    <blockquote class="m-0 flex-1 text-[19px] leading-[1.55] text-c2">{{ $ref->t('quote') }}</blockquote>
                    <figcaption class="flex items-center gap-3 border-t border-line-soft pt-[18px]">
                        @if ($ref->photo)
                            <img src="{{ asset('storage/'.$ref->photo) }}" alt="" width="44" height="44" loading="lazy" class="avatar-bw h-11 w-11 flex-none rounded-full object-cover">
                        @else
                            <span aria-hidden="true" class="inline-flex h-11 w-11 flex-none items-center justify-center rounded-full border border-dashed border-[rgba(var(--w),0.3)] bg-[rgba(var(--w),0.10)] font-mono text-[11px] font-medium text-c4">{{ __('site.refs.photo') }}</span>
                        @endif
                        <span class="flex flex-col leading-[1.35]">
                            <span class="font-semibold text-c0">{{ $ref->name }}</span>
                            <span class="font-mono text-xs text-c5">{{ $ref->t('title') }}</span>
                        </span>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </section>
@endif
