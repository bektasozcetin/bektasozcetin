<section id="kod" class="flex flex-col gap-10" x-data="{ tab: 'action' }">
    <x-section-head :eyebrow="__('site.code.eyebrow')" :a="__('site.code.title_a')" :b="__('site.code.title_b')">
        <x-slot:below><p class="lede max-w-[50ch]">{{ __('site.code.lede') }}</p></x-slot:below>
        <div role="group" aria-label="{{ __('site.code.label') }}" class="seg-group card flex gap-1 rounded-full p-[5px]">
            @foreach (config('showcase') as $key => $sample)
                <button type="button" class="seg-btn" @click="tab = '{{ $key }}'" :aria-pressed="(tab === '{{ $key }}').toString()">{{ $sample['label'] }}</button>
            @endforeach
        </div>
    </x-section-head>

    <div class="reveal">
    @foreach (config('showcase') as $key => $sample)
        <div class="flex flex-wrap items-stretch gap-[18px]" x-show="tab === '{{ $key }}'" @if (! $loop->first) x-cloak @endif>
            <div class="card relative min-w-0 flex-[999_1_640px] overflow-hidden rounded-[30px]" aria-live="polite">
                <div class="flex items-center gap-2 border-b border-line-soft px-5 py-3.5">
                    <span class="h-3 w-3 rounded-full bg-accent shadow-[0_0_10px_var(--vg)]"></span><span class="h-3 w-3 rounded-full bg-[rgba(var(--w),0.22)]"></span><span class="h-3 w-3 rounded-full bg-[rgba(var(--w),0.12)]"></span>
                    <span class="ml-3 font-mono text-xs font-medium text-c4">{{ $sample['file'] }}</span>
                </div>
                <div class="overflow-x-auto px-5 pt-5 pb-[26px] font-mono text-[13.5px] leading-[1.85]">
                    @foreach ($sample['lines'] as $n => $line)
                        @php $text = $line[0]; $hi = $line[1] ?? false; @endphp
                        <div class="codeline tline" style="animation-delay: {{ $n * 0.03 }}s"><span class="text-[#55575E] select-none">{{ $n + 1 }}</span><span @class(['text-accent-soft' => $hi, 'text-c5' => ! $hi && str_starts_with(trim($text), '//'), 'text-c2' => ! $hi && ! str_starts_with(trim($text), '//')])>{{ $text === '' ? ' ' : $text }}</span></div>
                    @endforeach
                </div>
            </div>
            <div class="card flex min-w-0 flex-[1_1_320px] flex-col gap-4 rounded-[30px] p-[26px]">
                <span class="lbl volt">{{ __('site.code.why') }}</span>
                <h3 class="h3">{{ __('site.code.samples.'.$key.'.title') }}</h3>
                <ul class="m-0 flex list-none flex-col gap-3.5 p-0">
                    @foreach (__('site.code.samples.'.$key.'.notes') as $note)
                        <li class="flex gap-3 text-[15px] text-c2"><span class="vdot mt-2"></span>{{ $note }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endforeach
    </div>
</section>
