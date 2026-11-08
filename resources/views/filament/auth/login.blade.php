@php
    $items = [
        ['01', 'Projeler ve galeri', 'Vaka çalışmaları, ekran görüntüleri'],
        ['02', 'Notlar', 'Markdown ile yaz, zamanla, yayınla'],
        ['03', 'Görüşme takvimi', 'Talepleri onayla, saatleri yönet'],
    ];
@endphp
<div class="bk-auth">
    {{-- Sol: marka paneli --}}
    <aside class="bk-brand bk-spot" aria-hidden="true">
        <div class="bk-brand-dots"></div>
        <div class="bk-brand-glow"></div>
        <div class="bk-brand-glow bk-brand-glow-2"></div>
        <div class="bk-scan"></div>

        <a href="{{ url('/') }}" class="bk-logo" tabindex="-1">
            <span class="bk-logo-ring"><img src="{{ asset('images/portrait.jpg') }}" alt="" width="36" height="36"></span>
            <span>bektas<span class="bk-serif bk-blue">.dev</span></span>
        </a>

        <div class="bk-brand-body">
            <span class="bk-meta">Yönetim paneli · Sayı 01</span>
            <p class="bk-brand-title">
                <span class="bk-line"><span style="--d: .25s">İçerik, notlar</span></span>
                <span class="bk-line"><span style="--d: .37s">ve <span class="bk-serif">görüşmeler</span></span></span>
                <span class="bk-line"><span style="--d: .49s">tek yerde.</span></span>
            </p>
            <ul class="bk-brand-list">
                @foreach ($items as $i => [$n, $t, $s])
                    <li style="--d: {{ 0.7 + $i * 0.1 }}s">
                        <span class="bk-num">{{ $n }}</span>
                        <span><b>{{ $t }}</b><small>{{ $s }}</small></span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="bk-brand-foot">
            <span class="bk-live"></span>Türkiye saatiyle <b x-data="{ t: '' }" x-init="const f = () => t = new Intl.DateTimeFormat('tr-TR', { hour: '2-digit', minute: '2-digit', timeZone: 'Europe/Istanbul' }).format(new Date()); f(); setInterval(f, 30000)" x-text="t"></b>
        </div>
        <div class="bk-wordmark">@foreach (mb_str_split('bektas.dev') as $i => $ch)<span style="--d: {{ 0.3 + $i * 0.05 }}s">{{ $ch }}</span>@endforeach</div>
    </aside>

    {{-- Sağ: giriş kartı --}}
    <section class="bk-panel">
        <a href="{{ url('/') }}" class="bk-logo bk-logo-mobile">
            <span class="bk-logo-ring"><img src="{{ asset('images/portrait.jpg') }}" alt="" width="36" height="36"></span>
            <span>bektas<span class="bk-serif bk-blue">.dev</span></span>
        </a>

        <div class="bk-card bk-spot">
            <div class="bk-head">
                <span class="bk-eyebrow"><i></i>Yönetim paneli</span>
                <h1 class="bk-title">Tekrar <span class="bk-serif">hoş geldin.</span></h1>
                <p class="bk-sub">Devam etmek için hesabınla giriş yap.</p>
            </div>

            <div class="bk-form">
                {{ $this->content }}
            </div>

            <div class="bk-card-foot">
                <a href="{{ url('/') }}">← Siteye dön</a>
                <span><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>Yalnızca yetkili hesap</span>
            </div>
        </div>

        <p class="bk-legal">© {{ date('Y') }} {{ config('site.name') }}</p>
    </section>

    <x-filament-actions::modals />

    {{-- Fareyi izleyen ışık (kart ve sol panel) --}}
    <script>
        (() => {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
            let frame = null;
            document.addEventListener('pointermove', (e) => {
                if (frame) return;
                frame = requestAnimationFrame(() => {
                    frame = null;
                    document.querySelectorAll('.bk-spot').forEach((el) => {
                        const r = el.getBoundingClientRect();
                        el.style.setProperty('--mx', e.clientX - r.left + 'px');
                        el.style.setProperty('--my', e.clientY - r.top + 'px');
                    });
                });
            }, { passive: true });
        })();
    </script>
</div>
