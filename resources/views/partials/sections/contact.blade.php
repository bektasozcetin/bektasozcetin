<section id="sss" class="flex flex-wrap items-start gap-14" x-data="{ open: 0 }">
    <x-section-head class="min-w-0 flex-[1_1_320px]" stack :eyebrow="__('site.faq.eyebrow')" :a="__('site.faq.title_a')" :b="__('site.faq.title_b')" :lede="__('site.faq.lede')" />
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

@php
    $types = __('site.contact.types');
    $errorsText = __('site.contact.errors');
@endphp
<section id="iletisim" class="card reveal relative flex flex-wrap items-stretch justify-between gap-11 overflow-hidden rounded-[40px] p-[clamp(22px,6vw,80px)]">
    <div class="flex min-w-0 flex-[1_1_400px] flex-col gap-[22px]">
        <span class="eyebrow"><b></b>{{ __('site.contact.eyebrow') }}</span>
        <h2 class="m-0 font-display text-[clamp(42px,6vw,80px)] leading-[0.95] font-bold tracking-[-0.055em] text-balance text-c0">
            {{ __('site.contact.title_a') }}<br><span class="serif text-[1.12em] font-normal tracking-[-0.02em]">{{ __('site.contact.title_b') }}</span>
        </h2>
        <div class="glass-dark flex flex-wrap items-center gap-x-4 gap-y-2 rounded-[18px] px-4 py-3 font-mono text-[13px] text-c3">
            <span class="inline-flex items-center gap-2"><span class="vdot live h-[7px] w-[7px]"></span>{{ __('site.contact.clock') }} <strong class="text-c0 tabular-nums" x-data="clock" x-text="time">--:--</strong></span>
            <span>{{ __('site.contact.reply', ['time' => config('site.reply_time.'.app()->getLocale())]) }}</span>
        </div>
        <div class="glass-dark mt-auto flex items-center gap-3.5 rounded-[22px] px-[18px] py-4">
            <span aria-hidden="true" class="inline-flex h-12 w-12 flex-none items-center justify-center rounded-full bg-accent text-white"><x-ico name="send" :size="18" /></span>
            <span class="flex min-w-0 flex-1 flex-col gap-0.5">
                <span class="font-mono text-xs text-c5">{{ __('site.contact.email') }}</span>
                <a href="mailto:{{ config('site.email') }}" class="font-mono text-base font-medium break-all text-c0 no-underline select-all">{{ config('site.email') }}</a>
            </span>
            <button type="button" @click="copyEmail" aria-label="{{ __('site.contact.copy') }}" class="card inline-flex h-11 w-11 flex-none cursor-pointer items-center justify-center rounded-full text-c0">
                <x-ico name="copy" />
            </button>
        </div>
        @if ($mode = booking_mode())
            <a href="{{ $mode === 'external' ? setting('booking_url') : '#iletisim' }}" @click="book($event)" class="glass-dark group flex items-center gap-3.5 rounded-[22px] px-[18px] py-4 no-underline" @if ($mode === 'external') target="_blank" rel="noopener" @endif>
                <span aria-hidden="true" class="inline-flex h-12 w-12 flex-none items-center justify-center rounded-full border border-[rgba(var(--w),0.18)] text-c0"><x-ico name="calendar" :size="19" /></span>
                <span class="flex min-w-0 flex-1 flex-col gap-0.5">
                    <span class="font-display text-base font-semibold tracking-[-0.02em] text-c0">{{ __('site.contact.or_calendar') }}</span>
                    <span class="text-sm text-c4">{{ __('site.contact.calendar_hint', ['min' => setting('booking_duration', 30)]) }}</span>
                </span>
                <span aria-hidden="true" class="inline-flex h-11 w-11 flex-none items-center justify-center rounded-full bg-accent text-white transition-transform duration-500 group-hover:translate-x-1"><x-ico name="arrow" /></span>
            </a>
        @endif
        @php $social = array_filter(config('site.social')); @endphp
        @if ($social)
            <div class="grid grid-cols-[repeat(auto-fit,minmax(100px,1fr))] gap-2.5">
                @foreach ($social as $name => $url)
                    <a class="btn glass justify-center px-2.5 text-c1" href="{{ $url }}" target="_blank" rel="noopener me">{{ $name }}</a>
                @endforeach
            </div>
        @endif
    </div>

    <form action="{{ localized_route('contact') }}" method="POST" novalidate aria-label="{{ __('site.contact.form_label') }}"
          class="glass-dark flex min-w-0 flex-[1_1_420px] flex-col gap-4 rounded-[32px] p-[clamp(18px,3vw,30px)]"
          x-data="contactForm(@js($errorsText), @js(__('site.contact.error')))" @submit.prevent="submit" @pick-type.window="type = $event.detail">
        @csrf

        @if (session('contact_sent'))
            <div x-init="sent = true"></div>
        @endif

        <div x-show="sent" x-cloak role="status" class="flex min-h-[420px] flex-1 flex-col items-center justify-center gap-4 text-center">
            <span class="pop inline-flex h-[84px] w-[84px] items-center justify-center rounded-full bg-accent text-white shadow-[0_0_60px_-6px_var(--vg)]"><x-ico name="check" :size="38" :stroke="2.6" /></span>
            <span class="font-display text-[26px] font-semibold tracking-[-0.03em] text-c0">{{ __('site.contact.sent_title') }}</span>
            <span class="max-w-[34ch] text-c4">{{ __('site.contact.sent_sub') }}</span>
            <button type="button" class="btn glass text-c1" @click="reset">{{ __('site.contact.new') }}</button>
        </div>

        <div x-show="!sent" class="flex flex-col gap-4">
            {{-- Bot tuzağı: insanlar görmez, botlar doldurur --}}
            <div class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off" x-model="website"></label></div>

            <div class="grid grid-cols-[repeat(auto-fit,minmax(min(100%,180px),1fr))] gap-3.5">
                <div class="flex flex-col gap-2">
                    <label class="lbl" for="f-name">{{ __('site.contact.name') }}</label>
                    <input id="f-name" name="name" class="field" type="text" autocomplete="name" placeholder="{{ __('site.contact.name_ph') }}" x-model="name" value="{{ old('name') }}"
                           :aria-invalid="(!!errors.name).toString()" aria-describedby="e-name" maxlength="120">
                    <span id="e-name" class="err" x-show="errors.name" x-text="errors.name" x-cloak></span>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="lbl" for="f-mail">{{ __('site.contact.email') }}</label>
                    <input id="f-mail" name="email" class="field" type="email" autocomplete="email" inputmode="email" placeholder="{{ __('site.contact.mail_ph') }}" x-model="email" value="{{ old('email') }}"
                           :aria-invalid="(!!errors.email).toString()" aria-describedby="e-mail" maxlength="190">
                    <span id="e-mail" class="err" x-show="errors.email" x-text="errors.email" x-cloak></span>
                </div>
            </div>

            <div class="flex flex-col gap-2.5">
                <span class="lbl" id="f-type">{{ __('site.contact.type') }}</span>
                <input type="hidden" name="project_type" :value="type">
                <div role="group" aria-labelledby="f-type" class="flex flex-wrap gap-2">
                    @foreach ($types as $key => $label)
                        <button type="button" class="pill" @click="type = '{{ $key }}'" :aria-pressed="(type === '{{ $key }}').toString()">{{ $label }}</button>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label class="lbl" for="f-msg">{{ __('site.contact.message') }}</label>
                <textarea id="f-msg" name="message" class="field min-h-[140px] resize-y" rows="5" placeholder="{{ __('site.contact.message_ph') }}" x-model="message" maxlength="5000"
                          :aria-invalid="(!!errors.message).toString()" aria-describedby="e-msg">{{ old('message') }}</textarea>
                <span id="e-msg" class="err" x-show="errors.message" x-text="errors.message" x-cloak></span>
            </div>

            <div class="flex flex-col gap-2">
                <label for="f-kvkk" class="flex min-h-11 cursor-pointer items-start gap-3 text-sm leading-[1.55] text-c3">
                    <input id="f-kvkk" name="kvkk" type="checkbox" value="1" x-model="kvkk" :aria-invalid="(!!errors.kvkk).toString()" aria-describedby="e-kvkk" class="m-0 h-[22px] w-[22px] flex-none accent-(--v)">
                    <span>{{ __('site.contact.kvkk_a') }} <a href="{{ localized_route('privacy') }}" class="volt" target="_blank">{{ __('site.contact.kvkk_link') }}</a> {{ __('site.contact.kvkk_b') }}</span>
                </label>
                <span id="e-kvkk" class="err" x-show="errors.kvkk" x-text="errors.kvkk" x-cloak></span>
            </div>

            <p class="err" x-show="errors.general" x-text="errors.general" x-cloak role="alert"></p>

            <button type="submit" class="btn btn-primary justify-center" :aria-busy="busy.toString()" :disabled="busy">
                <span x-text="busy ? @js(__('site.contact.sending')) : @js(__('site.contact.submit'))">{{ __('site.contact.submit') }}</span>
                <x-ico name="send" :size="18" />
            </button>
            <span class="text-center text-[13px] text-c5">{{ __('site.contact.privacy') }}</span>
        </div>
    </form>
</section>
