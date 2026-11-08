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

<section id="hakkimda" class="flex flex-wrap items-center gap-16">
    <div class="reveal flex min-w-0 flex-[1_1_380px] justify-center">
        <div class="relative w-[min(100%,460px)]">
            {{-- Çalışma ortamı fotoğrafı --}}
            <figure class="photo-wrap relative m-0 aspect-[4/5] overflow-hidden rounded-[36px] border border-line-soft bg-[#1F2024]">
                <img src="{{ asset('images/workspace.jpg') }}" alt="{{ __('site.about.photo_alt') }}" width="960" height="1200" loading="lazy" class="photo-grade h-full w-full object-cover">
                <span class="photo-tint"></span>
                <figcaption class="absolute top-5 left-5 inline-flex items-center gap-2 rounded-full border border-white/15 bg-black/50 px-3.5 py-2 font-mono text-[11px] font-medium tracking-[.08em] text-white backdrop-blur-md">
                    <span class="vdot live h-1.5 w-1.5"></span>{{ __('site.about.photo_caption', ['city' => config('site.city')]) }}
                </figcaption>
            </figure>

            {{-- Kimlik kartı: fotoğrafın altına biner --}}
            <div class="glass relative z-[1] mx-4 -mt-36 flex flex-col gap-[18px] rounded-[30px] p-6 sm:mx-0 sm:-mr-10 sm:ml-12">
            <div class="flex items-center gap-4">
                <span class="h-16 w-16 flex-none rounded-full bg-[conic-gradient(from_200deg,var(--c5),var(--c0),var(--v),var(--c0),var(--c5))] p-0.5">
                    <img src="{{ asset('images/portrait.jpg') }}" alt="" width="60" height="60" loading="lazy" class="block h-full w-full rounded-full object-cover object-[50%_26%]">
                </span>
                <span class="flex flex-col leading-[1.3]">
                    <span class="font-display text-xl font-semibold tracking-[-0.02em] text-c0">{{ config('site.name') }}</span>
                    <span class="font-mono text-[13px] text-c4">Full Stack Laravel Developer</span>
                </span>
            </div>
            <dl class="m-0 flex flex-col">
                @foreach (__('site.about.id_rows', ['city' => config('site.city').', Türkiye']) as [$k, $v])
                    <div class="flex justify-between gap-4 border-t border-line-soft py-3"><dt class="lbl">{{ $k }}</dt><dd class="m-0 text-right text-c1">{{ $v }}</dd></div>
                @endforeach
            </dl>
            <div aria-hidden="true" class="flex items-center justify-between font-mono text-[11px] tracking-[.14em] text-c5">
                <span>BEKTAS<span class="volt">.</span>DEV</span>
            </div>
            </div>
        </div>
    </div>
    <div class="reveal flex min-w-0 flex-[1_1_520px] flex-col gap-[26px]">
        <span class="eyebrow"><b></b>{{ __('site.about.eyebrow') }}</span>
        <h2 class="h2">{{ __('site.about.title_a') }} <span class="serif grad-static text-[1.1em]">{{ __('site.about.title_b') }}</span> {{ __('site.about.title_c') }}</h2>
        <p class="m-0 max-w-[58ch] text-lg leading-[1.75] text-c3">{{ __('site.about.p1') }}</p>
        <p class="m-0 max-w-[58ch] leading-[1.75] text-c4">{{ __('site.about.p2') }}</p>
        <div class="grid grid-cols-[repeat(auto-fit,minmax(min(100%,170px),1fr))] gap-3">
            @foreach (__('site.about.values') as [$title, $text])
                <div class="card spot flex flex-col gap-2 rounded-[22px] p-[18px]">
                    <span class="font-display text-base font-semibold text-c0">{{ $title }}</span>
                    <span class="text-sm text-c4">{{ $text }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section id="deneyim" class="flex flex-wrap items-start gap-14">
    <x-section-head class="min-w-0 flex-[1_1_320px]" stack :eyebrow="__('site.experience.eyebrow')" :a="__('site.experience.title_a')" :b="__('site.experience.title_b')" :lede="__('site.experience.lede')" />
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

<section id="calisma" class="flex flex-col gap-12">
    <x-section-head :eyebrow="__('site.models.eyebrow')" :a="__('site.models.title_a')" :b="__('site.models.title_b')" :lede="__('site.models.lede')" />
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

@if ($testimonials->isNotEmpty())
    <section id="referanslar" class="flex flex-col gap-12">
        <x-section-head stack :eyebrow="__('site.refs.eyebrow')" :a="__('site.refs.title_a')" :b="__('site.refs.title_b')" />
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
