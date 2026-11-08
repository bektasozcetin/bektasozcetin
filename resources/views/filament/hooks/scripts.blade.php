<script>
    (() => {
        const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const CARDS = '.fi-section, .fi-ta-ctn, .fi-wi-stats-overview-stat, .bk-welcome';

        // Kartlara fareyi izleyen ışık
        const mark = () => document.querySelectorAll(CARDS).forEach((el) => el.classList.add('bk-glow'));
        let frame = null;
        document.addEventListener('pointermove', (e) => {
            if (reduce || frame) return;
            frame = requestAnimationFrame(() => {
                frame = null;
                const el = e.target.closest?.(CARDS);
                if (!el) return;
                const r = el.getBoundingClientRect();
                el.style.setProperty('--mx', e.clientX - r.left + 'px');
                el.style.setProperty('--my', e.clientY - r.top + 'px');
            });
        }, { passive: true });

        // İstatistik değerleri sıfırdan sayarak gelir (1.287 gibi Türkçe biçim korunur)
        const countUp = () => {
            if (reduce) return;
            document.querySelectorAll('.fi-wi-stats-overview-stat-value:not([data-counted])').forEach((el) => {
                const text = el.textContent.trim();
                const target = parseInt(text.replace(/\D/g, ''), 10);
                if (!target || !/^[\d.]+$/.test(text)) return;
                el.dataset.counted = '1';
                const fmt = new Intl.NumberFormat('tr-TR');
                const start = performance.now();
                const dur = 1100;
                const step = (now) => {
                    const p = Math.min(1, (now - start) / dur);
                    el.textContent = fmt.format(Math.round(target * (1 - Math.pow(1 - p, 4))));
                    if (p < 1) requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            });
        };

        const init = () => { mark(); countUp(); };
        document.addEventListener('DOMContentLoaded', init);
        document.addEventListener('livewire:navigated', init);
        document.addEventListener('livewire:init', () => {
            window.Livewire?.hook?.('morph.updated', () => requestAnimationFrame(mark));
        });
        if (document.readyState !== 'loading') init();
    })();
</script>
