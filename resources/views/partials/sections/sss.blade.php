<section id="sss" class="flex flex-wrap items-start gap-14" x-data="{ open: 0 }">
    <x-section-head :level="$headLevel ?? 'h2'" class="min-w-0 flex-[1_1_320px]" stack :eyebrow="__('site.faq.eyebrow')" :a="__('site.faq.title_a')" :b="__('site.faq.title_b')" :lede="__('site.faq.lede')" />
    <div class="flex min-w-0 flex-[999_1_560px] flex-col gap-3">
        @foreach ($faqs as $i => $faq)
            <div class="card faq reveal overflow-hidden rounded-[22px]" :data-open="open === {{ $i }} ? '' : null">
                <h3 class="m-0">
                    <button type="button" id="faq-q-{{ $i }}" aria-controls="faq-a-{{ $i }}" :aria-expanded="(open === {{ $i }}).toString()" @click="open = open === {{ $i }} ? -1 : {{ $i }}"
                            class="flex min-h-[72px] w-full cursor-pointer items-center justify-between gap-4 border-0 bg-transparent px-6 py-5 text-left font-display text-lg font-semibold tracking-[-0.02em] text-c0">
                        <span>{{ $faq->t('question') }}</span>
                        <span class="plus inline-flex h-9 w-9 flex-none items-center justify-center rounded-full"><x-ico name="plus" /></span>
                    </button>
                </h3>
                <div id="faq-a-{{ $i }}" role="region" aria-labelledby="faq-q-{{ $i }}" x-show="open === {{ $i }}" x-collapse @if ($i !== 0) x-cloak @endif>
                    <p class="m-0 max-w-[64ch] px-6 pb-[22px] leading-[1.75] text-c4">{{ $faq->t('answer') }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>
