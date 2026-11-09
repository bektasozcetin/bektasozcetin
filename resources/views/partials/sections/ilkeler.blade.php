<section id="ilkeler" class="flex flex-col gap-7">
    <x-section-head class="mb-3" :eyebrow="__('site.principles.eyebrow')" :a="__('site.principles.title_a')" :b="__('site.principles.title_b')" :lede="__('site.principles.lede')" />
    <ol class="m-0 flex list-none flex-col border-b border-line p-0">
        @foreach (__('site.principles.items') as $i => [$title, $text])
            <li class="principle reveal">
                <span class="serif text-[clamp(44px,6vw,84px)] leading-[.9] text-[rgba(var(--w),0.18)]">0{{ $i + 1 }}</span>
                <span class="font-display text-[clamp(22px,2.6vw,32px)] leading-[1.15] font-semibold tracking-[-0.035em] text-c0">{{ $title }}</span>
                <p class="m-0 max-w-[46ch] text-base text-c4">{{ $text }}</p>
            </li>
        @endforeach
    </ol>
</section>
