@php
    $roles = ['Laravel Developer', 'Tailwind Craftsman', 'API Architect', 'Problem Solver'];
    $mast = mb_str_split('ÖZÇETİN');
@endphp
<section id="top" class="cover mt-[calc(clamp(20px,2.4vw,34px)-clamp(96px,11vw,150px))]">
    <div class="fade-up flex flex-wrap items-center justify-between gap-x-6 gap-y-2.5 border-b border-[rgba(var(--w),0.14)] pb-3.5 [grid-area:meta] [animation-delay:.05s]">
        <span class="meta">{{ __('site.cover.issue') }}</span>
        @if (config('site.available'))
            <span class="glass inline-flex items-center gap-2.5 rounded-full py-[7px] pr-3.5 pl-3 font-mono text-xs font-medium text-c2">
                <span class="vdot live"></span>{{ __('site.cover.available') }}
            </span>
        @endif
        <span class="meta">Full Stack · <span class="volt">Laravel</span></span>
        <span class="meta">{{ config('site.city') }}, Türkiye</span>
    </div>

    <h1 aria-label="{{ config('site.name') }}" class="relative z-[1] m-0 flex flex-col [grid-area:mast]">
        <span aria-hidden="true" class="serif fade-up mb-[0.34em] pl-[0.06em] text-[clamp(40px,5.6vw,84px)] leading-none text-c2 [animation-delay:.1s]">Bektaş</span>
        <span aria-hidden="true" class="mast-row">
            @foreach ($mast as $i => $ch)
                <span class="ch metal" style="animation-delay: {{ 0.2 + $i * 0.07 }}s">{{ $ch }}</span>
            @endforeach
        </span>
    </h1>

    <div class="cover-photo stage fade-up [animation-delay:.25s]">
        <div class="relative mx-auto aspect-[4/5] w-[min(100%,440px)] max-w-full">
            <div aria-hidden="true" class="absolute -inset-[14%] rounded-full bg-[radial-gradient(circle_at_50%_45%,var(--vg),transparent_62%)] blur-[40px]"></div>
            <div class="glass gb absolute inset-0 rounded-[40px] p-2.5">
                <div class="relative h-full w-full overflow-hidden rounded-[32px] bg-[#2A2B2F]">
                    <img class="portrait block h-full w-full object-cover object-[50%_28%]" src="{{ asset('images/portrait.jpg') }}" alt="{{ __('site.cover.portrait_alt') }}" width="440" height="550" fetchpriority="high">
                    <div aria-hidden="true" class="absolute inset-0 bg-[linear-gradient(180deg,rgba(var(--k2),0)_58%,rgba(var(--k2),0.85)_100%)]"></div>
                    <div aria-hidden="true" class="absolute inset-0 bg-[linear-gradient(90deg,transparent_70%,var(--vf))] mix-blend-screen"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="fade-up flex min-w-0 flex-col gap-[22px] self-end [grid-area:left] [animation-delay:.7s]" x-data="roles({{ count($roles) }})">
        <div class="flex flex-wrap items-baseline gap-2.5 font-display text-[clamp(20px,2.2vw,26px)] font-light tracking-[-0.02em] text-c3">
            <span>Full Stack</span>
            <span class="sr-only">{{ implode(', ', $roles) }}</span>
            <span aria-hidden="true" class="inline-block h-[1.3em] overflow-hidden align-bottom">
                <span class="role-roll" :style="`transform: translateY(${-1.3 * i}em)`">
                    @foreach ($roles as $role)
                        <span class="h-[1.3em] leading-[1.3em] font-semibold whitespace-nowrap text-c0">{{ $role }}</span>
                    @endforeach
                </span>
            </span>
        </div>
        <p class="m-0 max-w-[40ch] text-[17px] leading-[1.7] text-c4">{{ __('site.cover.lede_a') }} <span class="serif volt text-xl">{{ __('site.cover.lede_em') }}</span>{{ __('site.cover.lede_b') }}</p>
        <div class="flex flex-wrap items-center gap-2.5">
            <a class="btn btn-primary" href="#iletisim" @click="book($event)">{{ __('site.cta') }} <x-ico name="arrow" :size="18" /></a>
            @if ($cv = cv_url())
                <a class="navlink underline decoration-accent underline-offset-4" href="{{ $cv }}" download target="_blank">{{ __('site.cover.cv') }}</a>
            @endif
        </div>
    </div>

    <nav class="fade-up flex min-w-0 flex-col self-end [grid-area:right] [animation-delay:.85s]" aria-label="{{ __('site.cover.in_issue') }}">
        <span class="meta pb-2.5">{{ __('site.cover.in_issue') }}</span>
        @foreach (__('site.cover.lines') as $line)
            {{-- Satır ya ana sayfadaki bir bölüme (id) ya da bir alt sayfaya (page) gider. --}}
            <a class="coverline" href="{{ isset($line['page']) ? localized_route($line['page']) : '#'.$line['id'] }}">
                <span class="volt font-mono text-xs font-medium">{{ $line['n'] }}</span>
                <span class="flex min-w-0 flex-col leading-[1.3]">
                    <span class="font-display text-base font-semibold tracking-[-0.02em] text-c0">{{ str_replace(':count', $projectCount, $line['t']) }}</span>
                    <span class="text-[13px] text-c4">{{ $line['s'] }}@if (($line['id'] ?? null) === 'iletisim')<span x-data="clock" x-text="time">--:--</span>@endif</span>
                </span>
                <x-ico name="arrow" class="arr" />
            </a>
        @endforeach
    </nav>
</section>
