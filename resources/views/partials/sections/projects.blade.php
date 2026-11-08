@php
    $tints = ['linear-gradient(135deg,#3A3B40,#1C1D20)', 'linear-gradient(225deg,#34353A,#18191C)', 'linear-gradient(160deg,#2E2F33,#151619)', 'linear-gradient(300deg,#3C3D42,#1A1B1E)'];
    $lights = ['-right-20 -top-20', '-left-20 -top-[70px]', 'right-[20%] -top-[120px]', 'left-[30%] -bottom-[130px]'];
@endphp
<section id="projeler" class="flex flex-col gap-11" x-data="{ filter: 'all' }">
    <x-section-head :eyebrow="__('site.projects.eyebrow')" :a="__('site.projects.title_a')" :b="__('site.projects.title_b')" serif>
        <x-slot:below>
            <p class="lede">{{ __('site.projects.lede') }}</p>
            @if (config('site.show_sample_badge'))
                <span class="chip inline-flex items-center gap-2 self-start border-dashed leading-normal whitespace-normal"><span class="vdot h-1.5 w-1.5"></span>{{ __('site.projects.sample') }}</span>
            @endif
        </x-slot:below>
        <div role="group" aria-label="{{ __('site.projects.filter_label') }}" class="seg-group card flex gap-1 rounded-full p-[5px]">
            @foreach (__('site.projects.filters') as $key => $label)
                <button type="button" class="seg-btn" @click="filter = '{{ $key }}'" :aria-pressed="(filter === '{{ $key }}').toString()">{{ $label }}</button>
            @endforeach
        </div>
    </x-section-head>

    <div class="flex flex-col gap-[26px]">
        @foreach ($projects as $i => $project)
            @php $case = $project->toCaseStudy(); @endphp
            <article class="card spot reveal flex flex-wrap items-stretch gap-3.5 rounded-[36px] p-3.5 {{ $i % 2 ? 'flex-row-reverse' : '' }}"
                     x-show="filter === 'all' || filter === '{{ $project->category }}'" x-transition.opacity.duration.400ms>
                <div aria-hidden="true" class="relative aspect-[16/10] min-w-0 flex-[1_1_560px] overflow-hidden rounded-[26px] border border-line-soft sm:aspect-auto sm:min-h-[400px]" style="background: {{ $tints[$i % 4] }}">
                    @if ($case['image'])
                        <img src="{{ $case['image'] }}" alt="" loading="lazy" width="1600" height="1000" class="photo-grade thumb absolute inset-0 h-full w-full object-cover">
                        <span class="photo-tint"></span>
                    @else
                        <div class="absolute h-[340px] w-[340px] rounded-full bg-[radial-gradient(circle,var(--vg),transparent_65%)] {{ $lights[$i % 4] }}"></div>
                        <span class="serif absolute top-1.5 left-[22px] text-[120px] leading-none text-[rgba(var(--w),0.10)]">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="glass thumb absolute top-[74px] right-[8%] -bottom-[30px] left-[12%] flex flex-col rounded-[18px] bg-[rgba(var(--k),0.62)]">
                            <div class="flex items-center gap-1.5 border-b border-line-soft px-3.5 py-[11px]">
                                <span class="h-2 w-2 rounded-full bg-[rgba(var(--w),0.35)]"></span><span class="h-2 w-2 rounded-full bg-[rgba(var(--w),0.22)]"></span><span class="h-2 w-2 rounded-full bg-[rgba(var(--w),0.12)]"></span>
                                <span class="ml-2.5 h-3.5 flex-1 rounded-[7px] bg-fill"></span>
                            </div>
                            <div class="flex flex-1 gap-3.5 p-4">
                                <div class="flex w-[22%] flex-col gap-2.5">
                                    <span class="h-2 rounded bg-accent"></span><span class="h-2 rounded bg-[rgba(var(--w),0.14)]"></span><span class="h-2 rounded bg-[rgba(var(--w),0.14)]"></span><span class="h-2 w-[70%] rounded bg-[rgba(var(--w),0.14)]"></span>
                                </div>
                                <div class="grid flex-1 grid-cols-3 content-start gap-2.5">
                                    <span class="h-16 rounded-xl bg-[rgba(var(--w),0.07)]"></span><span class="h-16 rounded-xl bg-[rgba(var(--w),0.07)]"></span>
                                    <span class="h-16 rounded-xl bg-[linear-gradient(135deg,var(--v),var(--v2))] opacity-85"></span>
                                    <span class="col-span-3 h-[110px] rounded-xl bg-[linear-gradient(180deg,rgba(var(--w),0.09),rgba(var(--w),0.01))]"></span>
                                </div>
                            </div>
                        </div>
                    @endif
                    <span class="absolute top-[18px] right-[18px] rounded-full border border-white/15 bg-black/55 px-[11px] py-[5px] font-mono text-[11px] font-medium tracking-[.08em] text-white backdrop-blur-md">{{ $case['type'] }}</span>
                </div>
                <div class="flex min-w-0 flex-[1_1_340px] flex-col justify-center gap-4 p-[clamp(12px,2.4vw,28px)]">
                    <span class="meta">{{ $case['year'] }} · {{ $case['type'] }}</span>
                    <h3 class="m-0 font-display text-[clamp(26px,3vw,36px)] leading-[1.05] font-semibold tracking-[-0.04em] text-c0">{{ $case['title'] }}</h3>
                    <p class="m-0 text-c4">{{ $case['description'] }}</p>
                    <div class="flex flex-col gap-1 border-y border-line py-3.5">
                        <span class="lbl volt">{{ __('site.projects.result') }}</span>
                        <span class="serif text-[22px] leading-[1.25] text-c0">{{ $case['result'] }}</span>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach ($case['tags'] as $tag)<span class="chip">{{ $tag }}</span>@endforeach
                    </div>
                    <div class="flex flex-wrap items-center gap-1.5">
                        <a href="{{ $case['url'] }}" class="btn btn-light btn-sm" @click.prevent="openCase('{{ $project->slug }}')" aria-haspopup="dialog">
                            {{ __('site.projects.read_case') }} <x-ico name="arrow" />
                        </a>
                        @if ($case['live_url'])
                            <a class="navlink" href="{{ $case['live_url'] }}" target="_blank" rel="noopener" aria-label="{{ $case['title'] }} — {{ __('site.projects.live_demo') }}">{{ __('site.projects.live_demo') }}</a>
                        @endif
                        @if ($case['code_url'])
                            <a class="navlink" href="{{ $case['code_url'] }}" target="_blank" rel="noopener" aria-label="{{ $case['title'] }} — {{ __('site.projects.code') }}">{{ __('site.projects.code') }}</a>
                        @endif
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    @if ($repositories->isNotEmpty())
        <div class="reveal flex flex-col gap-[18px]">
            <div class="flex flex-wrap items-baseline justify-between gap-3">
                <h3 class="h3">{{ __('site.projects.os_a') }} <span class="serif text-[26px] text-c4">{{ __('site.projects.os_b') }}</span></h3>
                @if (config('site.social.GitHub'))
                    <a class="navlink" href="{{ config('site.social.GitHub') }}" target="_blank" rel="noopener">{{ __('site.projects.all_repos') }}</a>
                @endif
            </div>
            <div class="grid grid-cols-[repeat(auto-fit,minmax(min(100%,280px),1fr))] gap-3.5">
                @foreach ($repositories as $repo)
                    @php $tag = $repo->url ? 'a' : 'div'; @endphp
                    <{{ $tag }} class="card spot flex flex-col gap-3 rounded-[22px] p-5 no-underline" @if ($repo->url) href="{{ $repo->url }}" target="_blank" rel="noopener" @endif>
                        <span class="flex items-center gap-2.5 font-mono text-sm font-medium text-c0"><x-ico name="file" :size="18" :stroke="1.8" class="volt" />{{ $repo->name }}</span>
                        <span class="text-sm text-c4">{{ $repo->t('description') }}</span>
                        <span class="flex gap-3.5 font-mono text-xs text-c5">
                            <span>● {{ $repo->language }}</span>
                            @if ($repo->stars !== null)<span>★ {{ $repo->stars }} {{ __('site.projects.stars') }}</span>@endif
                            <span>{{ $repo->t('kind') }}</span>
                        </span>
                    </{{ $tag }}>
                @endforeach
            </div>
        </div>
    @endif
</section>

<section aria-label="{{ __('site.band.label') }}" class="card reveal relative flex flex-wrap items-center justify-between gap-5 rounded-[32px] px-[26px] py-[22px]">
    <div class="flex min-w-0 items-center gap-4">
        <span aria-hidden="true" class="inline-flex h-12 w-12 flex-none items-center justify-center rounded-full border border-[rgba(var(--w),0.16)]"><span class="vdot live"></span></span>
        <span class="flex min-w-0 flex-col">
            <span class="font-display text-xl font-semibold tracking-[-0.02em] text-c0">{{ __('site.band.title') }}</span>
            <span class="text-c4">{{ __('site.band.sub') }}</span>
        </span>
    </div>
    <a class="btn btn-primary" href="#iletisim" @click="book($event)">{{ __('site.cta') }} <x-ico name="arrow" :size="18" /></a>
</section>
