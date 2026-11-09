import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';

const data = JSON.parse(document.getElementById('site-data')?.textContent || '{}');
const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content;

/* Vaka penceresi açıldığında projenin görüntülenmesini say (oturum başına bir kez) */
const countView = (slug) => {
    if (!data.viewUrl || !slug) return;
    fetch(data.viewUrl.replace('__slug__', encodeURIComponent(slug)), {
        method: 'POST', keepalive: true, headers: { 'X-CSRF-TOKEN': csrf(), Accept: 'application/json' },
    }).catch(() => {});
};

/* Google Analytics 4: yalnızca ziyaretçi onay verirse yüklenir */
const loadGA = (id) => {
    if (!id || window.gtag) return;
    const s = document.createElement('script');
    s.async = true;
    s.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(id);
    document.head.appendChild(s);
    window.dataLayer = window.dataLayer || [];
    window.gtag = function () { window.dataLayer.push(arguments); };
    window.gtag('js', new Date());
    window.gtag('config', id, { anonymize_ip: true });
};

/* ---------------------------------------------------------------------------
   Sayfa geneli durum: tema, menü, palet, vaka penceresi, bildirim
   --------------------------------------------------------------------------- */
Alpine.data('site', () => ({
    theme: document.documentElement.classList.contains('theme-light') ? 'light' : 'dark',
    menu: false,
    palette: false,
    caseSlug: null,
    booking: false,
    consent: null,
    toast: '',
    // Sayfa bir ekran boyu kaydırılınca "başa dön" düğmesi belirir.
    scrolled: false,
    cases: Object.fromEntries((data.cases || []).map((c) => [c.slug, c])),

    get themeLabel() {
        return this.theme === 'light' ? data.i18n?.toDark : data.i18n?.toLight;
    },
    get current() {
        return this.caseSlug ? this.cases[this.caseSlug] : null;
    },
    get showConsent() {
        return !!data.ga && !this.consent;
    },

    init() {
        // "Başa dön" düğmesi: bir ekran boyu aşağı inilince görünür.
        const izle = () => { this.scrolled = window.scrollY > window.innerHeight * 0.8; };
        window.addEventListener('scroll', izle, { passive: true });
        izle();

        // Adres #proje-slug ile açılırsa vaka penceresini aç; geri tuşu pencereyi kapatsın.
        const open = () => {
            const slug = decodeURIComponent(location.hash.replace('#vaka-', ''));
            this.caseSlug = location.hash.startsWith('#vaka-') && this.cases[slug] ? slug : null;
            if (this.caseSlug) this.booking = false;
        };
        window.addEventListener('popstate', open);
        open();
        if (this.caseSlug) countView(this.caseSlug);

        try { this.consent = localStorage.getItem('analytics-consent'); } catch (e) {}
        if (this.consent === 'granted') loadGA(data.ga);
    },

    setConsent(value) {
        this.consent = value;
        try { localStorage.setItem('analytics-consent', value); } catch (e) {}
        if (value === 'granted') loadGA(data.ga);
    },

    // "Görüşme planla": site içi takvim, harici takvim ya da iletişim formu
    book(e) {
        const b = data.booking || {};
        if (b.mode === 'internal') {
            e?.preventDefault();
            this.menu = false;
            if (this.caseSlug) this.closeCase();
            this.lastFocus = document.activeElement;
            this.booking = true;
            this.focusDialog();
        } else if (b.mode === 'external' && b.url) {
            e?.preventDefault();
            window.open(b.url, '_blank', 'noopener');
        } else if (!e) {
            location.href = '/' + (data.locale === 'en' ? 'en' : '') + '#iletisim';
        }
    },
    closeBooking() {
        this.booking = false;
        this.$nextTick(() => this.lastFocus?.focus?.({ preventScroll: true }));
    },

    async copyLink() {
        try {
            await navigator.clipboard.writeText(location.href);
            this.notify(data.i18n?.linkCopied);
        } catch (e) {
            this.notify(data.i18n?.copyFail);
        }
    },

    toggleTheme() {
        this.theme = this.theme === 'light' ? 'dark' : 'light';
        document.documentElement.className = 'theme-' + this.theme + (document.documentElement.classList.contains('rv') ? ' rv' : '');
        try { localStorage.setItem('theme', this.theme); } catch (e) {}
    },

    openPalette() {
        this.menu = false;
        this.lastFocus = document.activeElement;
        this.palette = true;
    },
    closePalette() {
        this.palette = false;
        this.$nextTick(() => this.lastFocus?.focus?.({ preventScroll: true }));
    },

    openCase(slug) {
        this.lastFocus = document.activeElement;
        history.pushState(null, '', '#vaka-' + slug);
        this.caseSlug = slug;
        countView(slug);
        this.focusDialog();
    },
    // Vaka penceresinde sıradaki projeye geç (sondaysa başa döner)
    nextCase() {
        const slugs = Object.keys(this.cases);
        const next = slugs[(slugs.indexOf(this.caseSlug) + 1) % slugs.length];
        history.replaceState(null, '', '#vaka-' + next);
        this.caseSlug = next;
        countView(next);
        this.$nextTick(() => {
            document.querySelector('.overlay:not([style*="display: none"])')?.scrollTo({ top: 0, behavior: reducedMotion ? 'auto' : 'smooth' });
        });
    },
    closeCase() {
        if (location.hash.startsWith('#vaka-')) history.back();
        this.caseSlug = null;
        this.$nextTick(() => this.lastFocus?.focus({ preventScroll: true }));
    },

    notify(message) {
        clearTimeout(this.toastTimer);
        this.toast = message;
        this.toastTimer = setTimeout(() => (this.toast = ''), 2600);
    },

    async copyEmail() {
        try {
            await navigator.clipboard.writeText(data.email);
            this.notify(data.i18n?.copied);
        } catch (e) {
            this.notify(data.i18n?.copyFail);
        }
    },

    globalKey(e) {
        if (e.key === 'Escape') {
            if (this.palette) return this.closePalette();
            if (this.booking) return this.closeBooking();
            if (this.caseSlug) return this.closeCase();
            if (this.menu) return (this.menu = false);
        }
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            if (this.caseSlug) this.closeCase();
            if (this.booking) this.booking = false;
            this.palette ? this.closePalette() : this.openPalette();
        }
    },

    // Açılan pencerenin ilk odaklanabilir öğesine odaklan
    focusDialog() {
        this.$nextTick(() => setTimeout(() => document.querySelector('.overlay:not([style*="display: none"]) [role=dialog] button, .overlay:not([style*="display: none"]) [role=dialog] input')?.focus(), 50));
    },
}));

/* Komut paleti: arama, ok tuşları, seçim */
Alpine.data('palette', (items) => ({
    items,
    q: '',
    i: 0,

    init() {
        this.$watch('palette', (open) => {
            if (open) {
                this.q = '';
                this.i = 0;
                this.$nextTick(() => this.$refs.input?.focus());
            }
        });
    },

    get results() {
        const q = this.q.toLocaleLowerCase(data.locale);
        return this.items.filter((it) => (this.label(it) + ' ' + this.hint(it)).toLocaleLowerCase(data.locale).includes(q));
    },

    label(it) {
        return it.type === 'theme' ? this.themeLabel : it.label;
    },
    hint(it) {
        return it.hint;
    },

    onKey(e) {
        const n = this.results.length;
        if (e.key === 'Escape') return;
        if (!n) return;
        if (e.key === 'ArrowDown') { e.preventDefault(); this.i = (this.i + 1) % n; }
        if (e.key === 'ArrowUp') { e.preventDefault(); this.i = (this.i - 1 + n) % n; }
        if (e.key === 'Enter' && e.target === this.$refs.input) { e.preventDefault(); this.run(this.results[this.i]); }
    },

    run(it) {
        this.closePalette();
        if (it.type === 'section') {
            const el = document.getElementById(it.target);
            // Pencerenin kaydırma kilidi kalktıktan sonra kaydır
            if (el) setTimeout(() => el.scrollIntoView({ behavior: reducedMotion ? 'auto' : 'smooth' }), 80);
            else location.href = '/' + (data.locale === 'en' ? 'en' : '') + '#' + it.target;
        } else if (it.type === 'book') {
            setTimeout(() => this.book(), 80);
        } else if (it.type === 'copy') {
            this.copyEmail();
        } else if (it.type === 'link') {
            location.href = it.target;
        } else if (it.type === 'theme') {
            this.toggleTheme();
        }
    },
}));

/* Kapaktaki dönen unvanlar */
Alpine.data('roles', (count) => ({
    i: 0,
    init() {
        if (reducedMotion) return;
        setInterval(() => (this.i = (this.i + 1) % count), 2600);
    },
}));

/* Türkiye saati */
Alpine.data('clock', () => ({
    time: '--:--',
    init() {
        const tick = () => {
            this.time = new Intl.DateTimeFormat(data.locale === 'en' ? 'en-GB' : 'tr-TR', { hour: '2-digit', minute: '2-digit', timeZone: 'Europe/Istanbul' }).format(new Date());
        };
        tick();
        setInterval(tick, 30000);
    },
}));

/* İletişim formu: tarayıcıda ön doğrulama, sunucuya fetch ile gönderim */
Alpine.data('contactForm', (messages, generalError) => ({
    name: '', email: '', message: '', type: 'web', kvkk: false, website: '',
    errors: {},
    busy: false,
    sent: false,

    validate() {
        const errors = {};
        if (!this.name.trim()) errors.name = messages.name;
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email.trim())) errors.email = messages.email;
        if (this.message.trim().length < 10) errors.message = messages.message;
        if (!this.kvkk) errors.kvkk = messages.kvkk;
        this.errors = errors;
        const first = Object.keys(errors)[0];
        if (first) this.$el.querySelector(`[aria-describedby="e-${first === 'email' ? 'mail' : first === 'message' ? 'msg' : first}"]`)?.focus();
        return !first;
    },

    async submit() {
        if (this.busy || !this.validate()) return;
        this.busy = true;
        try {
            const res = await fetch(this.$el.action, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': csrf() },
                body: JSON.stringify({ name: this.name, email: this.email, message: this.message, project_type: this.type, kvkk: this.kvkk, website: this.website }),
            });
            if (res.ok) {
                this.sent = true;
            } else if (res.status === 422) {
                const body = await res.json();
                this.errors = Object.fromEntries(Object.entries(body.errors || {}).map(([k, v]) => [k, v[0]]));
            } else {
                this.errors = { general: generalError };
            }
        } catch (e) {
            this.errors = { general: generalError };
        } finally {
            this.busy = false;
        }
    },

    reset() {
        Object.assign(this, { name: '', email: '', message: '', kvkk: false, errors: {}, sent: false });
    },
}));

/* Görüşme takvimi: uygun günleri getir, gün + saat seç, talebi gönder */
Alpine.data('bookingForm', (cfg) => ({
    loading: false, loadError: false, loaded: false,
    days: [], duration: 30,
    day: null, time: null,
    name: '', email: '', topic: 'web', note: '', kvkk: false, website: '',
    errors: {}, busy: false, done: false,

    init() {
        this.$watch('booking', (open) => {
            if (open && (!this.loaded || this.done)) this.load();
        });
        if (this.booking) this.load();
    },

    async load() {
        if (this.done) Object.assign(this, { done: false, day: null, time: null, note: '', kvkk: false, errors: {} });
        this.loading = true;
        this.loadError = false;
        try {
            const res = await fetch(cfg.slotsUrl, { headers: { Accept: 'application/json' } });
            if (!res.ok) throw new Error(res.status);
            const body = await res.json();
            this.days = body.days || [];
            this.duration = body.duration || 30;
            this.loaded = true;
            const stillFree = this.days.find((d) => d.date === this.day && this.freeCount(d));
            if (!stillFree) {
                this.day = this.days.find((d) => this.freeCount(d))?.date || null;
                this.time = null;
            } else if (this.time && !stillFree.times.some(([t, free]) => t === this.time && free)) {
                this.time = null;
            }
        } catch (e) {
            this.loadError = true;
        } finally {
            this.loading = false;
        }
    },

    freeCount(d) {
        return d.times.filter(([, free]) => free).length;
    },
    get currentTimes() {
        return this.days.find((d) => d.date === this.day)?.times || [];
    },
    pickDay(date) {
        if (this.day !== date) this.time = null;
        this.day = date;
    },

    // "2026-11-10" → yerel biçim (saat dilimi kaymasın diye öğlen saatiyle)
    fmt(date, opts) {
        return new Intl.DateTimeFormat(cfg.locale === 'en' ? 'en-GB' : 'tr-TR', opts).format(new Date(date + 'T12:00:00'));
    },
    get whenLabel() {
        if (!this.day || !this.time) return '';
        return this.fmt(this.day, { weekday: 'long', day: 'numeric', month: 'long' }) + ', ' + this.time;
    },

    // Takvime eklemek için .ics dosyası (Türkiye saati UTC+3, yaz saati uygulaması yok)
    get icsHref() {
        if (!this.day || !this.time) return '#';
        const start = new Date(this.day + 'T' + this.time + ':00+03:00');
        const end = new Date(start.getTime() + this.duration * 60000);
        const stamp = (d) => d.toISOString().replace(/[-:]/g, '').replace(/\.\d{3}/, '');
        const ics = [
            'BEGIN:VCALENDAR', 'VERSION:2.0', 'PRODID:-//bektasozcetin.com//booking//TR', 'BEGIN:VEVENT',
            'UID:' + stamp(start) + '-' + Math.random().toString(36).slice(2) + '@bektasozcetin.com',
            'DTSTAMP:' + stamp(new Date()), 'DTSTART:' + stamp(start), 'DTEND:' + stamp(end),
            'SUMMARY:' + cfg.title, 'END:VEVENT', 'END:VCALENDAR',
        ].join('\r\n');
        return 'data:text/calendar;charset=utf-8,' + encodeURIComponent(ics);
    },

    validate() {
        const e = {};
        if (!this.day || !this.time) e.slot = cfg.errors.slot;
        if (!this.name.trim()) e.name = cfg.errors.name;
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email.trim())) e.email = cfg.errors.email;
        if (!this.kvkk) e.kvkk = cfg.errors.kvkk;
        this.errors = e;
        const first = ['name', 'email', 'kvkk'].find((k) => e[k]);
        if (first) document.getElementById({ name: 'b-name', email: 'b-mail', kvkk: 'b-kvkk' }[first])?.focus();
        return !Object.keys(e).length;
    },

    async submit() {
        if (this.busy || !this.validate()) return;
        this.busy = true;
        try {
            const res = await fetch(cfg.storeUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': csrf() },
                body: JSON.stringify({ date: this.day, time: this.time, name: this.name, email: this.email, topic: this.topic, note: this.note, kvkk: this.kvkk, website: this.website }),
            });
            if (res.ok) {
                this.done = true;
            } else if (res.status === 422) {
                const body = await res.json();
                this.errors = Object.fromEntries(Object.entries(body.errors || {}).map(([k, v]) => [k === 'date' || k === 'time' ? 'slot' : k, v[0]]));
                if (this.errors.slot) this.load();
            } else {
                this.errors = { general: cfg.error };
            }
        } catch (e) {
            this.errors = { general: cfg.error };
        } finally {
            this.busy = false;
        }
    },
}));

Alpine.plugin(focus);
Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();

/* ---------------------------------------------------------------------------
   Kaydırınca beliren bölümler (bir kez oynar, aynı satırdaki kartlar sırayla)
   --------------------------------------------------------------------------- */
function setupReveal() {
    if (reducedMotion || !('IntersectionObserver' in window)) return;

    const show = (el) => {
        const siblings = el.parentElement ? [...el.parentElement.children].filter((c) => c.classList.contains('reveal')) : [];
        const delay = Math.min(Math.max(0, siblings.indexOf(el)) * 90, 450);
        el.style.transitionDelay = delay + 'ms';
        el.setAttribute('data-in', '');
        setTimeout(() => (el.style.transitionDelay = ''), delay + 1200);
    };

    const io = new IntersectionObserver((entries) => {
        entries.forEach((e) => {
            if (e.isIntersecting) {
                show(e.target);
                io.unobserve(e.target);
            }
        });
    }, { rootMargin: '0px 0px -12% 0px', threshold: 0.08 });

    document.documentElement.classList.add('rv');
    document.querySelectorAll('.reveal').forEach((el) => {
        const r = el.getBoundingClientRect();
        if (r.height && r.top < window.innerHeight * 0.88 && r.bottom > 0) show(el);
        else io.observe(el);
    });
}
setupReveal();


/* Kartlardaki fare ışığı (requestAnimationFrame ile sınırlı) */
let spotFrame = null;
document.addEventListener('pointermove', (e) => {
    if (spotFrame) return;
    spotFrame = requestAnimationFrame(() => {
        spotFrame = null;
        const el = e.target.closest?.('.spot');
        if (!el) return;
        const r = el.getBoundingClientRect();
        el.style.setProperty('--mx', e.clientX - r.left + 'px');
        el.style.setProperty('--my', e.clientY - r.top + 'px');
    });
}, { passive: true });
