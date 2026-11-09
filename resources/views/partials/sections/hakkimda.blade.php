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
                <span>BEKTASOZCETIN<span class="volt">.</span>COM</span>
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
