<footer class="flex flex-col gap-6 pb-8">
    <div aria-hidden="true" class="-mx-2 overflow-hidden"><div class="wordmark">bektasozcetin.com</div></div>
    <div class="flex flex-wrap items-center justify-between gap-3 font-mono text-[13px] text-c5">
        <span>© {{ date('Y') }} {{ config('site.name') }} · {{ __('site.footer.made') }}</span>
        <div class="flex flex-wrap items-center gap-1.5">
            <a class="navlink [font:inherit]" href="{{ localized_route('privacy') }}">{{ __('site.footer.privacy') }}</a>
            @include('partials.lang-switch', ['class' => 'border border-line p-[3px]'])
            {{-- "Başa dön" artık sağ altta sabit duran düğmede: partials/floating --}}
        </div>
    </div>
</footer>
