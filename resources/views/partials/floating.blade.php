{{-- Sağ alt köşede sabit duran eylemler: WhatsApp ve başa dön. --}}
<div class="pointer-events-none fixed right-[max(16px,env(safe-area-inset-right))] bottom-[max(16px,env(safe-area-inset-bottom))] z-30 flex flex-col items-end gap-3">
    @if ($wa = config('site.whatsapp'))
        <a href="https://wa.me/{{ $wa }}"
           target="_blank" rel="noopener noreferrer"
           class="fab fab-wa pointer-events-auto"
           aria-label="{{ __('site.footer.whatsapp') }}"
           title="{{ __('site.footer.whatsapp') }}">
            <x-ico name="whatsapp" :size="24" :stroke="1.7" />
        </a>
    @endif

    <button type="button"
            class="fab pointer-events-auto"
            x-show="scrolled"
            x-cloak
            x-transition.opacity.duration.250ms
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            aria-label="{{ __('site.footer.top') }}"
            title="{{ __('site.footer.top') }}">
        <x-ico name="arrow-up" :size="20" :stroke="2.2" />
    </button>
</div>
