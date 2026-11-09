<section id="calisma" class="flex flex-col gap-12">
    <x-section-head :level="$headLevel ?? 'h2'" :eyebrow="__('site.models.eyebrow')" :a="__('site.models.title_a')" :b="__('site.models.title_b')" :lede="__('site.models.lede')" />
    <div class="grid grid-cols-[repeat(auto-fit,minmax(min(100%,300px),1fr))] items-stretch gap-[18px]">
        @foreach ($plans as $plan)
            <article @class(['card spot reveal flex flex-col gap-[22px] rounded-[32px] p-[30px]', 'border-accent!' => $plan->is_featured])>
                <div class="flex items-center justify-between gap-2.5">
                    <h3 class="h3">{{ $plan->t('title') }}</h3>
                    @if ($plan->t('badge'))
                        <span class="chip border-accent bg-accent text-white">{{ $plan->t('badge') }}</span>
                    @endif
                </div>
                <p class="m-0 text-c4">{{ $plan->t('description') }}</p>
                <div class="flex flex-col gap-0.5 border-y border-line-soft py-4">
                    <span class="lbl">{{ $plan->t('price_label') }}</span>
                    <span class="serif text-[34px] leading-[1.1] text-c0">{{ $plan->t('price') }}</span>
                </div>
                <ul class="m-0 flex flex-1 list-none flex-col gap-2.5 p-0">
                    @foreach ($plan->tList('items') as $item)
                        <li class="flex items-start gap-2.5 text-[15px] text-c2"><x-ico name="check" :size="18" :stroke="2.4" class="volt mt-[3px] flex-none" />{{ $item }}</li>
                    @endforeach
                </ul>
                <a href="#iletisim" @click="$dispatch('pick-type', '{{ $plan->contact_type }}')"
                   @class(['btn justify-center', 'btn-primary' => $plan->is_featured, 'border border-[rgba(var(--w),0.16)] bg-fill text-c1' => ! $plan->is_featured])>{{ $plan->t('cta') }}</a>
            </article>
        @endforeach
    </div>
    <div class="reveal flex flex-col gap-[18px]">
        <h3 class="h3">{{ __('site.models.process_a') }} <span class="serif text-[26px] text-c4">{{ __('site.models.process_b') }}</span></h3>
        @php $stepColors = ['#55575E', '#6E7078', 'var(--c5)', '#A9ACB4', 'var(--c2)', 'var(--v)']; @endphp
        <ol class="m-0 grid list-none grid-cols-[repeat(auto-fit,minmax(min(100%,170px),1fr))] gap-3 p-0">
            @foreach (__('site.models.steps') as $i => [$title, $text])
                <li class="card spot flex flex-col gap-3 overflow-hidden rounded-[22px] p-5">
                    <span aria-hidden="true" class="serif absolute -top-3.5 right-2.5 -z-10 text-[96px] leading-none text-[rgba(var(--w),0.05)]">0{{ $i + 1 }}</span>
                    <span @class(['inline-flex h-[34px] w-[34px] items-center justify-center rounded-full font-mono text-xs font-bold', 'text-white' => $i < 3 || $i === 5, 'text-ink' => $i >= 3 && $i < 5, 'shadow-[0_0_20px_var(--vg)]' => $i === 5]) style="background: {{ $stepColors[$i] }}">0{{ $i + 1 }}</span>
                    <span class="font-display text-[17px] font-semibold tracking-[-0.02em] text-c0">{{ $title }}</span>
                    <span class="text-sm text-c4">{{ $text }}</span>
                    <span aria-hidden="true" class="flow mt-auto h-0.5 w-full rounded-sm"></span>
                </li>
            @endforeach
        </ol>
    </div>
</section>
