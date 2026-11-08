<section id="hizmetler" class="flex flex-col gap-11">
    <x-section-head :eyebrow="__('site.services.eyebrow')" :a="__('site.services.title_a')" :b="__('site.services.title_b')" :lede="__('site.services.lede')" />
    <div class="grid grid-cols-[repeat(auto-fit,minmax(min(100%,340px),1fr))] gap-[18px]">
        @foreach ($services as $i => $service)
            <article class="card spot svc reveal flex flex-col gap-4 rounded-[30px] p-7">
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

<section id="yetkinlikler" class="flex flex-col gap-12">
    <x-section-head :eyebrow="__('site.skills.eyebrow')" :a="__('site.skills.title_a')" :b="__('site.skills.title_b')" :lede="__('site.skills.lede')" />
    <div class="bento">
        <article class="card spot reveal s7 flex flex-col gap-[26px] overflow-hidden rounded-[32px] p-[34px]">
            <div aria-hidden="true" class="absolute -top-[140px] -right-[100px] -z-10 h-[360px] w-[360px] rounded-full bg-[radial-gradient(circle,var(--vg),transparent_65%)] blur-[30px]"></div>
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="flex items-center gap-4">
                    <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-accent text-white shadow-[0_0_30px_var(--vg)]"><x-ico name="layers" :size="26" :stroke="1.7" /></span>
                    <div>
                        <h3 class="m-0 font-display text-[26px] font-semibold tracking-[-0.03em] text-c0">Laravel Backend</h3>
                        <span class="font-mono text-[13px] text-c5">PHP 8.3 · Laravel 12</span>
                    </div>
                </div>
                <span class="chip border-accent text-c0">{{ __('site.skills.core') }}</span>
            </div>
            <div class="grid grid-cols-[repeat(auto-fit,minmax(210px,1fr))] gap-x-6 gap-y-3">
                @foreach (__('site.skills.backend') as $item)
                    <div class="flex items-center gap-3 text-[15px] text-c2"><x-ico name="check" :size="18" :stroke="2.6" class="volt flex-none" />{{ $item }}</div>
                @endforeach
            </div>
<pre class="glass-dark m-0 overflow-x-auto rounded-2xl px-5 py-[18px] font-mono text-[13px] leading-[1.8] text-c3"><span class="c">// app/Http/Controllers/OrderController.php</span>
<span class="k">public function</span> <span class="f">store</span>(<span class="t">StoreOrderRequest</span> $request)
{
    $order = <span class="t">DB</span>::<span class="f">transaction</span>(<span class="k">fn</span> () =&gt; $request-&gt;<span class="f">user</span>()-&gt;<span class="f">orders</span>()-&gt;<span class="f">create</span>($request-&gt;<span class="f">validated</span>()));
    <span class="t">ProcessPayment</span>::<span class="f">dispatch</span>($order);
    <span class="k">return new</span> <span class="t">OrderResource</span>($order);
}</pre>
        </article>

        <article class="card spot reveal s5 flex flex-col gap-[22px] rounded-[32px] p-[34px]">
            <div class="flex items-center gap-4">
                <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl border border-line-strong bg-line-soft"><x-ico name="braces" :size="26" :stroke="1.6" /></span>
                <div>
                    <h3 class="m-0 font-display text-2xl font-semibold tracking-[-0.03em] text-c0">REST API</h3>
                    <span class="font-mono text-[13px] text-c5">Sanctum · Resources · v1</span>
                </div>
            </div>
            <div class="glass-dark flex flex-1 flex-col overflow-hidden rounded-2xl">
                <div class="flex flex-wrap items-center gap-2.5 border-b border-line-soft px-4 py-3 font-mono text-xs font-medium">
                    <span class="rounded-full bg-accent px-[9px] py-[3px] font-bold text-white">GET</span>
                    <span class="text-c2">/api/v1/orders/42</span>
                    <span class="ml-auto inline-flex items-center gap-1.5 text-c0"><span class="vdot live h-1.5 w-1.5"></span>200</span>
                </div>
<pre class="m-0 overflow-x-auto px-[18px] py-4 font-mono text-[13px] leading-[1.75] text-c3">{
  <span class="f">"data"</span>: {
    <span class="f">"id"</span>: <span class="k">42</span>,
    <span class="f">"status"</span>: <span class="s">"paid"</span>,
    <span class="f">"items"</span>: <span class="k">3</span>,
    <span class="f">"customer"</span>: {
      <span class="f">"name"</span>: <span class="s">"Ayşe K."</span>
    }
  }
}</pre>
            </div>
        </article>

        <article class="card spot reveal s4 flex flex-col gap-5 rounded-[32px] p-7">
            <div class="flex items-center gap-3.5">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-line-strong bg-line-soft"><x-ico name="wave" :size="24" :stroke="1.6" /></span>
                <div>
                    <h3 class="m-0 font-display text-[21px] font-semibold tracking-[-0.03em] text-c0">Tailwind UI</h3>
                    <span class="font-mono text-xs text-c5">Livewire · Alpine · Vue</span>
                </div>
            </div>
            <div class="glass-dark flex flex-col gap-3.5 rounded-2xl p-4" aria-hidden="true">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-fill font-mono text-[11px] font-bold text-c0">BÖ</span>
                    <div class="flex flex-1 flex-col gap-[7px]">
                        <span class="h-2 w-[62%] rounded bg-[rgba(var(--w),0.45)]"></span>
                        <span class="h-1.5 w-[40%] rounded bg-[rgba(var(--w),0.16)]"></span>
                    </div>
                    <span class="relative h-6 w-[42px] rounded-full bg-accent shadow-[0_0_14px_var(--vg)]"><span class="absolute top-[3px] right-[3px] h-[18px] w-[18px] rounded-full bg-white"></span></span>
                </div>
                <div class="flex gap-2">
                    <span class="h-9 flex-1 rounded-full bg-c0"></span>
                    <span class="h-9 flex-1 rounded-full border border-[rgba(var(--w),0.22)]"></span>
                </div>
            </div>
            <div class="flex gap-1.5" aria-hidden="true">
                <span class="h-[26px] flex-1 rounded-md bg-[#27282C]"></span><span class="h-[26px] flex-1 rounded-md bg-[#7A7D85]"></span><span class="h-[26px] flex-1 rounded-md bg-[#EDEDEF]"></span><span class="h-[26px] flex-1 rounded-md bg-accent"></span><span class="h-[26px] flex-1 rounded-md bg-accent-soft"></span>
            </div>
            <span class="font-mono text-xs leading-[1.6] text-c5">class="<span class="volt">rounded-3xl bg-white/5 backdrop-blur-2xl</span>"</span>
        </article>

        <article class="card spot reveal s4 flex flex-col gap-5 rounded-[32px] p-7">
            <div class="flex items-center gap-3.5">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-line-strong bg-line-soft"><x-ico name="db" :size="24" :stroke="1.6" /></span>
                <div>
                    <h3 class="m-0 font-display text-[21px] font-semibold tracking-[-0.03em] text-c0">{{ __('site.skills.db') }}</h3>
                    <span class="font-mono text-xs text-c5">MySQL · PostgreSQL · Redis</span>
                </div>
            </div>
            <div class="relative flex flex-col gap-3 pl-[18px]">
                <div aria-hidden="true" class="vflow absolute top-[18px] bottom-[18px] left-[5px] w-0.5 rounded-sm"></div>
                @foreach ([['users', '1 ─┐', 'id · name · email · role'], ['orders', '├─ n', 'id · user_id · total · status'], ['order_items', '└─ n', 'id · order_id · product_id · qty']] as [$table, $rel, $cols])
                    <div class="glass-dark flex flex-col gap-1 rounded-[14px] px-3.5 py-2.5 font-mono text-xs">
                        <div class="flex justify-between gap-2"><span class="font-bold text-c0">{{ $table }}</span><span class="volt">{{ $rel }}</span></div>
                        <span class="text-c4">{{ $cols }}</span>
                    </div>
                @endforeach
            </div>
        </article>

        <article class="card spot reveal s4 flex flex-col gap-5 rounded-[32px] p-7">
            <div class="flex items-center gap-3.5">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-line-strong bg-line-soft"><x-ico name="box" :size="24" :stroke="1.6" /></span>
                <div>
                    <h3 class="m-0 font-display text-[21px] font-semibold tracking-[-0.03em] text-c0">DevOps</h3>
                    <span class="font-mono text-xs text-c5">Docker · Forge · Actions</span>
                </div>
            </div>
            <div class="flex flex-col gap-[9px]">
                @foreach ([['composer install', 'build'], ['php artisan test', 'test'], ['npm run build', 'assets'], ['migrate --force', 'db'], ['deploy → production', 'live']] as $j => [$cmd, $stage])
                    <div class="glass-dark flex items-center gap-2.5 rounded-[14px] px-3 py-[9px] font-mono text-[12.5px] text-c2">
                        <span @class(['inline-flex h-5 w-5 flex-none items-center justify-center rounded-full', 'bg-accent text-white' => $j === 4, 'bg-c0 text-ink' => $j !== 4])><x-ico name="check" :size="11" :stroke="3.4" /></span>
                        <span class="min-w-0 flex-1">{{ $cmd }}</span>
                        <span class="text-c5">{{ $stage }}</span>
                    </div>
                @endforeach
            </div>
        </article>
    </div>
</section>

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
