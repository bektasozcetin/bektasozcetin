<section id="deneyim" class="flex flex-wrap items-start gap-14">
    <x-section-head :level="$headLevel ?? 'h2'" class="min-w-0 flex-[1_1_320px]" stack :eyebrow="__('site.experience.eyebrow')" :a="__('site.experience.title_a')" :b="__('site.experience.title_b')" :lede="__('site.experience.lede')" />
    <div class="flex min-w-0 flex-[999_1_560px] flex-col gap-10">
        <div class="relative flex flex-col gap-[18px]">
            <div aria-hidden="true" class="vflow absolute top-6 bottom-6 left-[21px] w-0.5 rounded-sm"></div>
            @foreach ($experiences as $xp)
                <div class="reveal flex items-start gap-[22px]">
                    <span aria-hidden="true" class="card relative mt-[18px] inline-flex h-11 w-11 flex-none items-center justify-center rounded-full">
                        <span @class(['h-3 w-3 rounded-full', 'bg-accent live' => $xp->is_current, 'bg-c0' => $loop->index === 1 && ! $xp->is_current, 'bg-[#6E7078]' => $loop->index > 1 && ! $xp->is_current])></span>
                    </span>
                    <article class="card spot flex min-w-0 flex-1 flex-col gap-3 rounded-[22px] px-[26px] py-6">
                        <div class="flex flex-wrap items-center justify-between gap-2.5">
                            <h3 class="m-0 font-display text-[21px] font-semibold tracking-[-0.02em] text-c0">{{ $xp->role }}</h3>
                            <span class="chip">{{ $xp->t('period') }}</span>
                        </div>
                        <span class="serif text-[21px] leading-[1.2] text-c3">{{ $xp->t('company') }}</span>
                        <p class="m-0 text-c4">{{ $xp->t('description') }}</p>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($xp->tags ?? [] as $tag)<span class="chip">{{ $tag }}</span>@endforeach
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
        @if ($educations->isNotEmpty())
            <div class="reveal flex flex-col gap-3.5">
                <h3 class="h3">{{ __('site.experience.edu_title') }}</h3>
                <div class="grid grid-cols-[repeat(auto-fit,minmax(min(100%,240px),1fr))] gap-3">
                    @foreach ($educations as $edu)
                        <div class="card flex flex-col gap-1.5 rounded-[22px] p-5">
                            <span class="lbl volt">{{ $edu->t('kind') }}</span>
                            <span class="font-display text-base font-semibold text-c0">{{ $edu->t('title') }}</span>
                            <span class="text-sm text-c4">{{ $edu->t('subtitle') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
