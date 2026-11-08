@if ($brands->isNotEmpty())
    <section aria-label="{{ __('site.brands.label') }}" class="reveal -mt-10 flex flex-col gap-6">
        <div class="flex flex-wrap items-baseline justify-between gap-3 border-t border-line pt-6">
            <span class="meta">{{ __('site.brands.label') }}</span>
            <span class="font-mono text-xs text-c5">{{ __('site.brands.count', ['count' => $brands->count()]) }}</span>
        </div>
        <ul class="m-0 grid list-none grid-cols-2 gap-px overflow-hidden rounded-[22px] border border-line bg-line p-0 sm:grid-cols-3 lg:grid-cols-6">
            @foreach ($brands as $brand)
                <li class="brand-cell flex h-24 items-center justify-center bg-[rgba(var(--k2),1)] px-5 [.theme-light_&]:bg-[#FBFAF6]">
                    @php $tag = $brand->url ? 'a' : 'span'; @endphp
                    <{{ $tag }} @if ($brand->url) href="{{ $brand->url }}" target="_blank" rel="noopener" @endif class="flex items-center justify-center" title="{{ $brand->name }}">
                        @if ($brand->logo)
                            <img src="{{ asset('storage/'.$brand->logo) }}" alt="{{ $brand->name }}" class="brand-logo" loading="lazy">
                        @else
                            <span class="font-display text-lg font-semibold text-c3">{{ $brand->name }}</span>
                        @endif
                    </{{ $tag }}>
                </li>
            @endforeach
        </ul>
    </section>
@endif
