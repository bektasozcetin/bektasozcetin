<footer class="flex flex-col gap-6 pb-8">
    <div aria-hidden="true" class="-mx-2 overflow-hidden"><div class="wordmark">bektas.dev</div></div>
    <div class="flex flex-wrap items-center justify-between gap-3 font-mono text-[13px] text-c5">
        <span>© {{ date('Y') }} {{ config('site.name') }} · {{ __('site.footer.made') }}</span>
        <div class="flex flex-wrap items-center gap-1.5">
            <a class="navlink [font:inherit]" href="{{ localized_route('privacy') }}">{{ __('site.footer.privacy') }}</a>
            @include('partials.lang-switch', ['class' => 'border border-line p-[3px]'])
            <a class="navlink [font:inherit]" href="#top">{{ __('site.footer.top') }}</a>
        </div>
    </div>
</footer>
