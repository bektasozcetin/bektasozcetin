<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Faq;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\Repository;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/*
| Demo içerik: kurgusal projeler, kişiler ve şirketler; görseller Unsplash'ten.
| Yayından önce hepsini Filament panelinden (/admin) gerçek içerikle değiştir.
*/
class PortfolioSeeder extends Seeder
{
    private function t(string $tr, ?string $en = null): array
    {
        return ['tr' => $tr, 'en' => $en ?? $tr];
    }

    public function run(): void
    {
        $this->fresh();
        $this->brands();
        $this->projects();
        $this->services();
        $this->skills();
        $this->repositories();
        $this->experience();
        $this->testimonials();
        $this->faqs();
        $this->settings();
        $this->meetings();
    }

    /**
     * Bu seeder listeleri create() ile yazıyor; temizlemeden yeniden çalıştırmak
     * her kaydı çoğaltırdı. Projeler hariç tutuldu: onlar slug ile güncelleniyor.
     */
    private function fresh(): void
    {
        foreach ([Brand::class, Service::class, Skill::class, Repository::class, Experience::class, Education::class, Testimonial::class, Faq::class, Meeting::class] as $model) {
            $model::query()->delete();
        }
    }

    private function projects(): void
    {
        $t = fn ($tr, $en = null) => $this->t($tr, $en);

        $projects = [
            [
                'slug' => 'randevu-ajandam', 'category' => 'web', 'year' => '2026',
                'type' => $t('SAAS'),
                'title' => $t('Randevu Ajandam'),
                'description' => $t('Hekim ve klinikler için online randevu platformu. Hasta tarafındaki site, hekim ve klinik panelleri, hekime özel web sitesi ve doktor mobil uygulaması tek veri modeli üzerinde çalışır.', 'An online booking platform for doctors and clinics. The patient-facing site, the doctor and clinic panels, each doctor’s own website and the mobile app all run on a single data model.'),
                'result' => $t('Beş ayrı uygulama tek veri modeli ve birbirinden ayrı üç kimlik akışı üzerinden çalışıyor', 'Five separate applications run on one data model with three independent identity flows'),
                'role' => $t('Full stack · mimari kararlar dahil', 'Full stack, including architecture decisions'),
                'duration' => $t('Sürüyor', 'Ongoing'),
                'problem' => $t('Randevu talepleri telefon, WhatsApp ve kâğıt ajanda arasında dağılıyordu. Gelmeyen hasta artıyor, hasta kaydı ile gelir takibi ayrı yerlerde tutuluyordu. Hekimin kendi web sitesi varsa oradaki randevu talebi de panele düşmüyordu.', 'Booking requests were scattered across phone calls, WhatsApp and paper diaries. No-shows kept rising, patient records and income tracking lived in different places, and requests from a doctor’s own website never reached the panel.'),
                'approach' => $t('Bütün Eloquent modellerini tek bir ana uygulamada topladım. Hekim web siteleri ve halka açık site için model taşımayan ayrı bir API servisi, mobil uygulama için de sürümlü (api/mobile/v1) route’lar açtım. Hekim, klinik personeli ve hasta için ayrı token tabloları ve ayrı ara katmanlar kurdum.', 'I kept every Eloquent model in one core application, exposed a separate model-free API service for doctor websites and the public site, and versioned mobile routes under api/mobile/v1. Doctors, clinic staff and patients each got their own token table and middleware.'),
                'lesson' => $t('Alt projelere bölmek panelleri bağımsız geliştirmeyi kolaylaştırdı, ama tek doğruluk kaynağının ana uygulama olduğunu yazılı bir mimari kurala bağlamam gerekti; bunu baştan yapmadığım için bir süre modeller iki yere sızdı.', 'Splitting the system into sub-projects made each panel easier to work on, but I had to write down the rule that the core app is the single source of truth — until I did, models leaked into two places.'),
                'decisions' => [
                    ['q' => $t('Modeller nerede dursun?', 'Where should the models live?'), 'a' => $t('Hepsi ana uygulamada. Aracı API servisi model içermez, yalnızca denetleyici ve route taşır; şema tek yerden değişir.', 'All in the core app. The intermediary API service has no models, only controllers and routes, so the schema changes in one place.')],
                    ['q' => $t('Üç kullanıcı tipi tek token tablosuyla mı?', 'One token table for three user types?'), 'a' => $t('Hayır: hekim, personel ve hasta için ayrı tablo ve ayrı ara katman. Bir akıştaki hata diğerine yetki sızdırmıyor.', 'No: a separate table and middleware for each. A mistake in one flow cannot leak access into another.')],
                ],
                'tags' => ['Laravel', 'REST API', 'React Native · Expo', 'FCM', 'PayTR', 'Çok uygulamalı'],
            ],
            [
                'slug' => 'arac-servis', 'category' => 'panel', 'year' => '2026',
                'type' => $t('YÖNETİM SİSTEMİ', 'MANAGEMENT SYSTEM'),
                'title' => $t('Araç Servis Yönetim Sistemi', 'Vehicle Service Management System'),
                'description' => $t('Bir oto servisin gün içindeki tüm işleyişi tek sistemde: randevu, fotoğraflı araç kabul, iş emri, stok, tezgâh satışı, e-Fatura, kasa ve mali müşavir için ayrı salt okunur giriş.', 'Everything an auto repair shop does in a day, in one system: bookings, photographed vehicle intake, work orders, stock, counter sales, e-invoicing, cash desk, and a separate read-only login for the accountant.'),
                'result' => $t('500.000 iş emirlik veride bütün liste ve arama ekranları 300 ms’nin altında açılıyor; en yavaşı 207 ms', 'With 500,000 work orders in the database every list and search screen opens in under 300 ms; the slowest is 207 ms'),
                'role' => $t('Tek geliştirici · uçtan uca', 'Sole developer · end to end'),
                'duration' => $t('Sürüyor', 'Ongoing'),
                'problem' => $t('Servis, satış, finans, personel ve web sitesi ayrı ayrı yürüyordu. Aynı kayıt birkaç yere elle giriliyor, kullanılan parçanın stoktan düşmesi atlanıyor, mali müşavir veriyi ancak dönem sonunda görüyordu.', 'Service, sales, finance, staff and the website all ran separately. The same record was keyed in several times, parts were not always deducted from stock, and the accountant only saw the data at period end.'),
                'approach' => $t('İş kurallarını app/Actions altında tek giriş noktasında topladım; Livewire ekranları da mobil API de aynı eylemleri çağırıyor. İzinler config/yetkiler.php dosyasında, menüler ayrı yapılandırma dosyalarında tanımlı. Modeller alan bazlı klasörlere ayrıldı.', 'I collected the business rules into single entry points under app/Actions, called by both the Livewire screens and the mobile API. Permissions live in config/yetkiler.php, menus in their own config files, and models are grouped by domain.'),
                'lesson' => $t('Yetki matrisini testle sabitlemek en çok işe yarayan karar oldu. Rol × ekran denetimi otomatik çalıştığı için yeni modül eklerken yetki sızması derleme aşamasında yakalanıyor.', 'Pinning the permission matrix down with tests paid off most: because every role × screen combination is checked automatically, a new module cannot quietly open a door it should not.'),
                'decisions' => [
                    ['q' => $t('İş kuralları nerede yaşasın?', 'Where should business rules live?'), 'a' => $t('app/Actions içinde. Livewire bileşeni de mobil API de aynı eylemi çağırıyor, kural tek yerde kalıyor.', 'In app/Actions. The Livewire component and the mobile API call the same action, so the rule exists once.')],
                    ['q' => $t('Performans nasıl güvenceye alınsın?', 'How do you keep performance honest?'), 'a' => $t('Ayrı bir veritabanında 100.000 araç ve 500.000 iş emri üreten yük testi komutu; geliştirmede Model::shouldBeStrict() ile N+1 sorgular hata sayılıyor.', 'A load-test command generates 100,000 vehicles and 500,000 work orders in a separate database, and Model::shouldBeStrict() turns N+1 queries into errors during development.')],
                ],
                'tags' => ['Laravel 13', 'Livewire 4', 'Tailwind 4', 'Redis', 'Reverb', 'Pest · 376 test'],
            ],
            [
                'slug' => 'kurumsal-surec-uygulamalari', 'category' => 'panel', 'year' => '2025',
                'type' => $t('KURUMSAL', 'ENTERPRISE'),
                'title' => $t('Kurumsal süreç uygulamaları', 'Enterprise process applications'),
                'description' => $t('Kurum içi başvuru, onay ve takip süreçleri için geliştirdiğim çok rollü Laravel uygulamaları. Talep ve proje takibi, başvuru değerlendirme, kayıt arşivi ve toplu bildirim akışları aynı çekirdek üzerine kuruldu.', 'Multi-role Laravel applications for internal request, approval and tracking processes: request and project tracking, application review, record archives and bulk notification flows, all built on the same core.'),
                'result' => $t('Kâğıt ve e-tablo ile yürüyen başvuru süreçleri tek sistemde, zaman damgalı ve raporlanabilir hâle geldi', 'Processes that ran on paper and spreadsheets became one system, time-stamped and reportable'),
                'role' => $t('Backend ve panel geliştirme', 'Backend and panel development'),
                'duration' => $t('Farklı dönemlerde birden çok proje', 'Several projects over different periods'),
                'problem' => $t('Başvurular e-posta, dilekçe ve e-tablolar arasında dağılıyordu. Bir talebin hangi aşamada olduğunu, kimin ne zaman onayladığını ve ne kadar beklediğini görmek mümkün değildi.', 'Applications were scattered across email, paper forms and spreadsheets. Nobody could see which stage a request was at, who approved it and when, or how long it had been waiting.'),
                'approach' => $t('Her süreç için aynı çekirdeği kullandım: durum akışı, izin bazlı yetkilendirme, zaman damgalı işlem geçmişi ve Excel/PDF çıktısı. Yeni bir süreç eklemek yeni bir ekran takımı yazmak değil, mevcut yapıyı yapılandırmak oldu.', 'Every process reuses one core: a state flow, permission-based authorisation, a time-stamped history and Excel/PDF output. Adding a process became a matter of configuring what exists rather than writing new screens.'),
                'lesson' => $t('Süreci kullanıcıyla birlikte haritalamadan ekran yazmamak gerektiğini burada öğrendim. İlk projede varsaydığım onay adımları gerçekte başka sırayla işliyordu ve akışı sonradan söküp yeniden kurmak gerekti.', 'This is where I learned not to build screens before mapping the process with the people who use it. In the first project the approval steps ran in a different order than I had assumed, and the flow had to be rebuilt.'),
                'decisions' => [
                    ['q' => $t('Her süreç için ayrı uygulama mı, ortak çekirdek mi?', 'A separate app per process, or a shared core?'), 'a' => $t('Ortak çekirdek: durum akışı, yetki ve raporlama bir daha yazılmıyor, farklar yapılandırmayla çözülüyor.', 'A shared core: the state flow, permissions and reporting are written once, and differences are handled through configuration.')],
                    ['q' => $t('Yetkiler rol bazlı mı, izin bazlı mı?', 'Role-based or permission-based access?'), 'a' => $t('İzin bazlı: kişiye özel istisnalar rol yapısını bozmadan verilip geri alınabiliyor.', 'Permission-based: individual exceptions can be granted and revoked without breaking the role structure.')],
                ],
                'tags' => ['Laravel', 'Çok rollü', 'Süreç akışı', 'İzin bazlı yetki', 'Excel · PDF'],
            ],
        ];

        // Yerleri yeni gerçek projelere bırakan kurgusal demo kayıtları.
        Project::whereIn('slug', ['atolye-market', 'teklif-masasi', 'siradaki', 'rota-mobil-api'])->delete();

        foreach ($projects as $i => $project) {
            $project['image'] = $this->demoImage("projects/{$project['slug']}.jpg");
            $project['gallery'] = array_values(array_filter([
                $this->demoImage("gallery/{$project['slug']}-1.jpg", 'projects/gallery'),
                $this->demoImage("gallery/{$project['slug']}-2.jpg", 'projects/gallery'),
            ]));
            Project::updateOrCreate(['slug' => $project['slug']], $project + ['sort' => $i]);
        }
    }

    private function services(): void
    {
        $t = fn ($tr, $en = null) => $this->t($tr, $en);
        $rows = [
            ['calendar', 'randevu-rezervasyon',
                $t('Randevu ve rezervasyon sistemleri', 'Booking and reservation systems'),
                $t('Takvim, çalışma saatleri, izin ve tatil kuralları, çift kayıt engeli, SMS hatırlatma ve bekleme listesi. Hekim, klinik, salon ve etkinlik senaryolarına göre kurgulanır.', 'Calendars, working hours, leave and holiday rules, double-booking protection, SMS reminders and waiting lists, shaped around clinics, salons or events.'),
                ['Takvim', 'SMS hatırlatma', 'Bekleme listesi']],
            ['panel', 'yonetim-sistemi',
                $t('Sektörel yönetim sistemleri', 'Industry management systems'),
                $t('İş emri, stok, satış, fatura ve kasa akışının tek veritabanında birleştiği rol bazlı paneller. Bir kayıt bir kez girilir, her ekranda doğru görünür.', 'Role-based panels where work orders, stock, sales, invoicing and the cash desk share one database. A record is entered once and reads correctly everywhere.'),
                ['Livewire · Filament', 'Rol bazlı yetki', 'e-Fatura']],
            ['layers', 'kurumsal-kamu',
                $t('Kurumsal süreç ve başvuru uygulamaları', 'Enterprise process and application systems'),
                $t('Talep, inceleme, onay ve arşiv adımlarından oluşan çok rollü süreçler. Zaman damgalı işlem geçmişi, izin bazlı yetkilendirme ve Excel/PDF raporlar.', 'Multi-role processes built from request, review, approval and archive steps, with a time-stamped history, permission-based access and Excel/PDF reports.'),
                ['Süreç akışı', 'İzin bazlı yetki', 'Raporlama']],
            ['api', 'api-mobil',
                $t('REST API ve mobil backend', 'REST APIs and mobile backends'),
                $t('Mobil uygulamalar ve üçüncü taraflar için sürümlü, tutarlı yanıt veren ve testli API’ler. Token tabanlı kimlik doğrulama, push bildirim ve hız sınırı dahil.', 'Versioned, consistent and tested APIs for mobile apps and third parties, including token authentication, push notifications and rate limiting.'),
                ['Sürümlü API', 'Bearer token', 'FCM push']],
            ['cart', 'saas-abonelik',
                $t('Çok kiracılı SaaS ve abonelik', 'Multi-tenant SaaS and subscriptions'),
                $t('Paket, deneme süresi, yenileme ve iptal akışı; Türkiye’deki ödeme sağlayıcılarıyla 3D Secure entegrasyonu. Her kiracı yalnızca kendi verisini görür.', 'Plans, trials, renewals and cancellations, with 3D Secure integration for Turkish payment providers. Each tenant sees only its own data.'),
                ['Çok kiracılı', 'PayTR · iyzico', '3D Secure']],
            ['gauge', 'bakim-performans',
                $t('Bakım, sürüm yükseltme ve performans', 'Maintenance, upgrades and performance'),
                $t('Eski Laravel sürümlerinden güncele testlerle güvenceye alınmış kademeli geçiş. Yavaş sayfaların nedenini bulurum: N+1 sorgular, eksik indeksler, cache ve kuyruk fırsatları — yazılı raporla.', 'Step-by-step, test-backed upgrades from old Laravel versions, plus written reports on why pages are slow: N+1 queries, missing indexes, caching and queue opportunities.'),
                ['Laravel 8 → 12', 'N+1 denetimi', 'Yük testi']],
        ];

        foreach ($rows as $i => [$icon, $slug, $title, $desc, $tags]) {
            Service::create([
                'icon' => $icon,
                'image' => $this->demoImage("services/{$slug}.jpg"),
                'title' => $title,
                'description' => $desc,
                'tags' => $tags,
                'sort' => $i,
            ]);
        }
    }

    /** Teknoloji yığını (panel → İçerik → Yetenekler). */
    private function skills(): void
    {
        $rows = [
            'backend' => [['PHP 8.3', 95, 7, true], ['Laravel', 95, 6, true], ['Livewire', 88, 4], ['Pest / PHPUnit', 85, 4], ['Queue & Horizon', 84, 4], ['Sanctum / API', 86, 5]],
            'frontend' => [['Tailwind CSS', 92, 5, true], ['Alpine.js', 86, 4], ['Blade', 93, 6], ['Vue 3', 78, 3], ['Inertia', 76, 3]],
            'data' => [['MySQL', 88, 6], ['PostgreSQL', 78, 3], ['Redis', 82, 4], ['Elasticsearch', 62, 2]],
            'devops' => [['Docker', 78, 3], ['GitHub Actions', 84, 4], ['Laravel Forge', 86, 5], ['Nginx', 72, 4], ['AWS', 64, 2]],
            'tools' => [['Filament', 92, 4, true], ['Git', 90, 7], ['Figma', 62, 3]],
        ];
        $sort = 0;
        foreach ($rows as $group => $skills) {
            foreach ($skills as $row) {
                [$name, $level, $years] = $row;
                Skill::create(['group' => $group, 'name' => $name, 'level' => $level, 'years' => $years, 'is_core' => $row[3] ?? false, 'sort' => $sort++]);
            }
        }
    }

    private function repositories(): void
    {
        $t = fn ($tr, $en = null) => $this->t($tr, $en);
        $rows = [
            ['bektasozcetin/laravel-iyzico', $t('iyzico ödeme, iade ve abonelik işlemleri için test edilmiş, kuyruk destekli Laravel paketi.', 'A tested, queue-friendly Laravel package for iyzico payments, refunds and subscriptions.'), $t('Laravel paketi', 'Laravel package'), 214],
            ['bektasozcetin/filament-activity-timeline', $t('Filament kayıtlarına kim, neyi, ne zaman değiştirdi zaman çizelgesi ekleyen eklenti.', 'A Filament plugin that adds a who-changed-what-and-when timeline to any record.'), $t('Filament eklentisi', 'Filament plugin'), 96],
            ['bektasozcetin/laravel-starter', $t('Roller, Pest testleri, GitHub Actions ve Docker hazır gelen başlangıç şablonu.', 'A starter kit that ships with roles, Pest tests, GitHub Actions and Docker.'), $t('Şablon', 'Template'), 58],
        ];
        // Kurgusal kayıtlar: gizli duruyorlar. Gerçek repolar yayınlanınca panelden
        // "GitHub'dan eşitle" ile çekilip bunların yerini alacak.
        foreach ($rows as $i => [$name, $desc, $kind, $stars]) {
            Repository::create(['name' => $name, 'description' => $desc, 'kind' => $kind, 'stars' => $stars, 'forks' => intdiv($stars, 7), 'pushed_at' => now()->subDays([3, 12, 27][$i] ?? 30), 'sort' => $i, 'is_published' => false]);
        }
    }

    private function experience(): void
    {
        $t = fn ($tr, $en = null) => $this->t($tr, $en);
        $rows = [
            [$t('2024 — Bugün', '2024 — Present'), 'Full Stack Laravel Developer', $t('Nexa Dijital · Ankara', 'Nexa Digital · Ankara'), $t('E-ticaret ve SaaS müşterileri için Laravel ve Livewire ile uçtan uca uygulamalar geliştiriyorum. API tasarımı, Filament panelleri ve GitHub Actions ile otomatik deploy süreçlerinden sorumluyum.', 'I build end-to-end Laravel and Livewire apps for e-commerce and SaaS clients, and own API design, Filament panels and automated deploys with GitHub Actions.'), ['Laravel', 'Livewire', 'Filament', 'Docker'], true],
            [$t('2022 — 2024'), 'Backend Developer', $t('Pazarium Teknoloji · Uzaktan', 'Pazarium Technology · Remote'), $t('Günde 15 binden fazla siparişi işleyen pazar yeri altyapısında REST API geliştirme, sorgu optimizasyonu ve kargo/ödeme entegrasyonları.', 'REST API development, query tuning and shipping/payment integrations for a marketplace handling 15,000+ orders a day.'), ['PHP', 'Laravel', 'MySQL', 'Redis'], false],
            [$t('2020 — 2022'), 'Freelance Web Developer', $t('Serbest', 'Independent'), $t('Küçük işletmeler için 20\'den fazla web sitesi, online mağaza ve yönetim paneli teslim ettim.', 'Delivered 20+ websites, online shops and admin panels for small businesses.'), ['Laravel', 'Blade', 'Tailwind'], false],
        ];
        foreach ($rows as $i => [$period, $role, $company, $desc, $tags, $current]) {
            Experience::create(compact('period', 'role', 'company', 'tags') + ['description' => $desc, 'is_current' => $current, 'sort' => $i]);
        }

        $edu = [
            [$t('Eğitim', 'Education'), $t('Bilgisayar Mühendisliği', 'Computer Engineering (BSc)'), $t('Dokuz Eylül Üniversitesi · 2020', 'Dokuz Eylül University · 2020')],
            [$t('Sertifika', 'Certificate'), $t('AWS Certified Cloud Practitioner'), $t('Amazon Web Services · 2023')],
            [$t('Topluluk', 'Community'), $t('“Filament ile 10 dakikada admin panel” sunumu', 'Talk: “An admin panel in 10 minutes with Filament”'), $t('Laravel Ankara Meetup · 2024')],
        ];
        foreach ($edu as $i => [$kind, $title, $subtitle]) {
            Education::create(compact('kind', 'title', 'subtitle') + ['sort' => $i]);
        }
    }

    private function testimonials(): void
    {
        $t = fn ($tr, $en = null) => $this->t($tr, $en);
        $rows = [
            ['Elif Kaya', 'elif.jpg', $t('Kurucu · Atölye Market', 'Founder · Atölye Market'),
                $t('Satıcı panelini ve ödeme akışını sıfırdan kurdu. İlk ay ödeme adımındaki terk oranı neredeyse yarıya indi; satıcılarımız artık stoklarını tek ekrandan yönetiyor.', 'He built the seller panel and checkout from scratch. In the first month checkout abandonment almost halved, and our sellers now manage stock from a single screen.')],
            ['Murat Demir', 'murat.jpg', $t('Satış Direktörü · Arkon Endüstri', 'Sales Director · Arkon Endüstri'),
                $t('Excel\'de saatler süren teklif hazırlığı artık dakikalar sürüyor. Her teklifin geçmişi kayıt altında; ekibe yeni katılanlar bile ilk gün kullanabiliyor.', 'Quotes that took hours in Excel now take minutes. Every change is on record, and even new hires can use it on day one.')],
            ['Zeynep Arslan', 'zeynep.jpg', $t('Mobil Ekip Lideri · Rota', 'Mobile Team Lead · Rota'),
                $t('API, dokümantasyonu ve testleriyle birlikte geldi. Mobil tarafta tahmin etmek zorunda kaldığımız tek bir endpoint kalmadı; yanıt süreleri de gözle görülür şekilde hızlandı.', 'The API arrived with docs and tests. There isn\'t a single endpoint left that the mobile team has to guess about, and responses got noticeably faster.')],
        ];
        foreach ($rows as $i => [$name, $photo, $title, $quote]) {
            Testimonial::create(['name' => $name, 'photo' => $this->demoImage("testimonials/{$photo}"), 'title' => $title, 'quote' => $quote, 'sort' => $i]);
        }
    }

    private function brands(): void
    {
        $rows = [
            ['nexa-dijital', 'Nexa Dijital'],
            ['pazarium', 'Pazarium'],
            ['atolye-market', 'Atölye Market'],
            ['arkon-endustri', 'Arkon Endüstri'],
            ['rota', 'Rota'],
            ['siradaki', 'Sıradaki'],
        ];
        foreach ($rows as $i => [$file, $name]) {
            Brand::create(['name' => $name, 'logo' => $this->demoImage("brands/{$file}.svg"), 'sort' => $i]);
        }
    }

    /** Demo görselini public diske kopyalar ve yolunu döndürür (storage git'e girmez). */
    /** Demo dosyasını public diske kopyalar; $dir verilirse o klasöre koyar. */
    private function demoImage(string $path, ?string $dir = null): ?string
    {
        $source = __DIR__.'/demo/'.$path;
        if (! is_file($source)) {
            return null;
        }
        $target = $dir ? $dir.'/'.basename($path) : $path;
        Storage::disk('public')->put($target, file_get_contents($source));

        return $target;
    }

    /** Demo CV dosyaları (panel → Site ayarları'ndan değiştirilir). */
    private function settings(): void
    {
        Setting::put([
            'cv_tr' => $this->demoImage('cv/bektas-ozcetin-cv-tr.pdf', 'cv'),
            'cv_en' => $this->demoImage('cv/bektas-ozcetin-cv-en.pdf', 'cv'),
        ]);
    }

    /** Takvimde dolu görünen birkaç örnek görüşme. */
    private function meetings(): void
    {
        $tz = 'Europe/Istanbul';
        $day = now($tz)->toImmutable()->startOfDay();
        $workdays = [];
        while (count($workdays) < 4) {
            $day = $day->addDay();
            if ($day->isWeekday()) {
                $workdays[] = $day;
            }
        }

        $rows = [
            [$workdays[0], '11:00', 'Kerem Aydın', 'kerem@ornek-sirket.test', 'shop', 'Mevcut WooCommerce mağazamızı Laravel\'e taşımak istiyoruz.', 'confirmed'],
            [$workdays[0], '14:30', 'Derya Şen', 'derya@ornek-ajans.test', 'admin', 'Ajansımız için müşteri ve iş takibi paneli.', 'pending'],
            [$workdays[1], '10:00', 'Mert Can Öz', 'mert@ornek-startup.test', 'api', 'Mobil uygulamamız için API ve admin panel.', 'pending'],
            [$workdays[2], '15:30', 'Selin Arı', 'selin@ornek-klinik.test', 'web', 'Klinik randevu sistemi için fiyat almak istiyorum.', 'confirmed'],
        ];
        foreach ($rows as [$date, $time, $name, $email, $topic, $note, $status]) {
            Meeting::create([
                'starts_at' => $date->setTimeFromTimeString($time)->setTimezone(config('app.timezone')),
                'duration' => 30, 'name' => $name, 'email' => $email, 'topic' => $topic, 'note' => $note, 'status' => $status,
            ]);
        }
    }

    private function faqs(): void
    {
        $t = fn ($tr, $en = null) => $this->t($tr, $en);
        $rows = [
            [$t('Hangi tür projeler alıyorsun?', 'What kind of projects do you take on?'), $t("Laravel tabanlı web uygulamaları, e-ticaret siteleri, SaaS ürünleri, admin panelleri ve REST API'ler. Mevcut projelere bakım ve yeni özellik geliştirme de yapıyorum.", 'Laravel web apps, online shops, SaaS products, admin panels and REST APIs. I also maintain and extend existing projects.')],
            [$t('Bir proje ne kadar sürer?', 'How long does a project take?'), $t('Kapsama göre değişir. Küçük bir site birkaç hafta, kapsamlı bir uygulama birkaç ay sürebilir. İlk görüşmede birlikte net bir takvim çıkarıyoruz.', 'It depends on scope. A small site can take a few weeks, a larger app a few months. We set a clear timeline in our first call.')],
            [$t('Mevcut bir Laravel projesini devralabilir misin?', 'Can you take over an existing Laravel project?'), $t('Evet. Önce kodu inceliyorum, ardından güncelleme, güvenlik ve performans iyileştirmeleri için bir plan hazırlıyorum.', 'Yes. I review the code first, then plan upgrades, security and performance work.')],
            [$t('Türk ödeme ve fatura sistemleriyle çalışıyor musun?', 'Do you work with Turkish payment and invoicing systems?'), $t("Evet. Projeye göre yerel ödeme sağlayıcıları, e-Fatura/e-Arşiv ve kargo firmalarının API'leriyle entegrasyon yapıyorum.", 'Yes. Depending on the project I integrate local payment providers, e-Invoice/e-Archive services and shipping company APIs.')],
            [$t('Kodun ve sunucunun sahibi kim olur?', 'Who owns the code and the server?'), $t('Sen. Kod senin GitHub hesabında, sunucu senin hesabında durur. Teslimde tüm erişimler ve dokümantasyon sende olur.', 'You do. The code lives in your GitHub, the server in your account, and you get every credential and the documentation at handover.')],
            [$t('Teslimden sonra destek veriyor musun?', 'Do you offer support after launch?'), $t('Bakım ve destek seçeneklerini proje başında birlikte belirliyoruz. İstersen aylık bakım modeliyle devam edebiliriz.', 'We agree on support at the start. If you like, we can continue with the monthly care model.')],
        ];
        foreach ($rows as $i => [$q, $a]) {
            Faq::create(['question' => $q, 'answer' => $a, 'sort' => $i]);
        }
    }
}
