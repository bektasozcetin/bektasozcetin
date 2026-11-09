<section id="hizmetler" class="flex flex-col gap-11">
    <x-section-head :level="$headLevel ?? 'h2'" :eyebrow="__('site.services.eyebrow')" :a="__('site.services.title_a')" :b="__('site.services.title_b')" :lede="__('site.services.lede')" />
    <div class="grid grid-cols-[repeat(auto-fit,minmax(min(100%,340px),1fr))] gap-[18px]">
        @foreach ($services as $i => $service)
            <article class="card spot svc reveal flex flex-col gap-4 rounded-[30px] p-7">
                @if ($service->image)
                    <div class="relative -mx-1 -mt-1 overflow-hidden rounded-[22px] border border-line-soft">
                        <img src="{{ asset('storage/'.$service->image) }}" alt="" loading="lazy" decoding="async" width="1200" height="750" class="photo-grade block aspect-[16/10] w-full object-cover">
                        <span class="photo-tint"></span>
                    </div>
                @endif
                <div class="flex items-start justify-between gap-3">
                    <span class="svc-ico inline-flex h-[52px] w-[52px] items-center justify-center rounded-2xl border border-line-strong bg-line-soft">
                        <x-ico :name="$service->icon" :size="24" :stroke="1.7" />
                    </span>
                    <span class="serif text-[40px] leading-none text-[rgba(var(--w),0.14)]">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                </div>
                <h3 class="h3">{{ $service->t('title') }}</h3>
                <p class="m-0 flex-1 text-c4">{{ $service->t('description') }}</p>
                <div class="flex flex-wrap gap-1.5">
                    @foreach ($service->tags ?? [] as $tag)<span class="chip">{{ $tag }}</span>@endforeach
                </div>
            </article>
        @endforeach
    </div>
</section>
