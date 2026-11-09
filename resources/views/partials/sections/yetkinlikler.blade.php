<section id="yetkinlikler" class="flex flex-col gap-12">
    <x-section-head :level="$headLevel ?? 'h2'" :eyebrow="__('site.skills.eyebrow')" :a="__('site.skills.title_a')" :b="__('site.skills.title_b')" :lede="__('site.skills.lede')" />
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

    @if ($skills->isNotEmpty())
        <div class="card spot reveal relative flex flex-col gap-8 overflow-hidden rounded-[32px] p-[clamp(22px,3vw,36px)]">
            <div aria-hidden="true" class="pointer-events-none absolute -bottom-40 -left-24 h-[380px] w-[380px] rounded-full bg-[radial-gradient(circle,var(--vg),transparent_65%)] opacity-60 blur-[30px]"></div>
            <div class="relative flex flex-wrap items-end justify-between gap-4">
                <div class="flex flex-col gap-2">
                    <h3 class="h3 text-[26px]">{{ __('site.skills.stack_a') }} <span class="serif text-[1.15em] font-normal text-c3">{{ __('site.skills.stack_b') }}</span></h3>
                    <p class="m-0 max-w-[52ch] text-c4">{{ __('site.skills.stack_lede') }}</p>
                </div>
                <span class="chip inline-flex items-center gap-2"><span class="vdot live h-1.5 w-1.5"></span>{{ __('site.skills.legend') }}</span>
            </div>
            <div class="relative grid grid-cols-[repeat(auto-fill,minmax(min(100%,250px),1fr))] gap-x-10 gap-y-8">
                @foreach (['backend', 'frontend', 'data', 'devops', 'tools'] as $group)
                    @continue(! $skills->has($group))
                    <div class="flex flex-col gap-3.5">
                        <span class="lbl flex items-center justify-between border-b border-line pb-2.5"><span class="volt">{{ __('site.skills.groups.'.$group) }}</span><span>{{ $skills[$group]->count() }}</span></span>
                        @foreach ($skills[$group] as $n => $skill)
                            <div class="skill {{ $skill->is_core ? 'is-core' : '' }}" style="--lv: {{ $skill->level }}%; --dl: {{ $n * 70 }}ms">
                                <div class="flex items-baseline justify-between gap-3">
                                    <span class="flex items-center gap-2 text-[15px] font-medium text-c1">@if ($skill->is_core)<span class="vdot live h-1.5 w-1.5"></span>@endif{{ $skill->name }}</span>
                                    <span class="font-mono text-[11.5px] text-c5">@if ($skill->years){{ __('site.skills.years', ['n' => $skill->years]) }} · @endif%{{ $skill->level }}</span>
                                </div>
                                <span class="meter" role="meter" aria-valuenow="{{ $skill->level }}" aria-valuemin="0" aria-valuemax="100" aria-label="{{ $skill->name }} · {{ __('site.skills.level') }}"><i></i></span>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</section>
