<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Faq;
use App\Models\Meeting;
use App\Models\PricingPlan;
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
        $this->brands();
        $this->projects();
        $this->services();
        $this->skills();
        $this->repositories();
        $this->experience();
        $this->pricing();
        $this->testimonials();
        $this->faqs();
        $this->settings();
        $this->meetings();
    }

    private function projects(): void
    {
        $t = fn ($tr, $en = null) => $this->t($tr, $en);

        $projects = [
            [
                'slug' => 'atolye-market', 'category' => 'web', 'year' => '2026',
                'type' => $t('E-TİCARET', 'E-COMMERCE'),
                'title' => $t('Atölye Market'),
                'description' => $t("El yapımı ürün satan 240'tan fazla bağımsız üretici için çok satıcılı pazar yeri: ürün, sepet, iyzico ile ödeme, kargo ve e-Fatura entegrasyonu.", 'Multi-vendor marketplace for 240+ independent makers of handmade goods: products, cart, iyzico payments, shipping and e-Invoice integration.'),
                'result' => $t("Ödeme adımında terk oranı %31'den %18'e düştü", 'Checkout abandonment fell from 31% to 18%'),
                'role' => $t('Tek geliştirici · uçtan uca', 'Sole developer · end to end'),
                'duration' => $t('5 ay', '5 months'),
                'problem' => $t('Satıcılar stoklarını e-tablolarda tutuyor, siparişler WhatsApp ile e-posta arasında kayboluyordu. Ödeme sayfası mobilde 4 saniyede açılıyor, her üç alıcıdan biri bu adımda vazgeçiyordu.', 'Sellers kept stock in spreadsheets and orders got lost between WhatsApp and email. Checkout took 4 seconds to load on mobile and one in three buyers gave up there.'),
                'approach' => $t('Sipariş, stok ve ödeme akışını tek bir Laravel uygulamasında topladım. Arayüzü Livewire ile kurdum, ödeme ve fatura işlemlerini kuyruğa aldım.', 'I brought orders, stock and payments into one Laravel app, built the interface with Livewire and moved payments and invoicing onto queues.'),
                'lesson' => $t('Kargo entegrasyonunu en sona bıraktım; firmaların test ortamları beklediğimden yavaş çıktı. Bugün olsa dış servis entegrasyonlarıyla başlardım.', "I left the shipping integration for last and the carriers' sandboxes were slower than expected. Today I'd start with third-party integrations."),
                'decisions' => [
                    ['q' => $t('Livewire mı, Inertia + Vue mu?', 'Livewire or Inertia + Vue?'), 'a' => $t('Livewire: ekip küçüktü, tek dilde daha hızlı geliştirme ve daha az bakım.', 'Livewire: a small team, one language, faster development and less upkeep.')],
                    ['q' => $t('Ödemeler senkron mu, kuyrukta mı?', 'Payments inline or queued?'), 'a' => $t('Kuyrukta (Horizon): ödeme sağlayıcısı yavaşladığında sipariş akışı kilitlenmiyor.', 'Queued (Horizon): a slow payment provider no longer blocks the order flow.')],
                ],
                'tags' => ['Laravel', 'Livewire', 'Tailwind', 'MySQL', 'Horizon', 'iyzico'],
            ],
            [
                'slug' => 'teklif-masasi', 'category' => 'panel', 'year' => '2025',
                'type' => $t('ADMIN PANEL'),
                'title' => $t('Teklif Masası'),
                'description' => $t('12 kişilik bir satış ekibi için müşteri, teklif ve fatura takibi yapan, rol bazlı yönetim paneli.', 'Role-based panel for a 12-person sales team to track customers, quotes and invoices.'),
                'result' => $t('Teklif hazırlama süresi 45 dakikadan 12 dakikaya indi', 'Preparing a quote went from 45 minutes to 12'),
                'role' => $t('Backend ve panel geliştirme', 'Backend and panel development'),
                'duration' => $t('10 hafta', '10 weeks'),
                'problem' => $t('Teklifler Excel şablonlarında hazırlanıyor, fiyat listesi her dosyada farklı oluyordu. Kimin hangi teklifi değiştirdiği bilinmiyordu.', 'Quotes were built from Excel templates, the price list differed in every file and nobody knew who changed which quote.'),
                'approach' => $t('Filament üzerine rol bazlı bir panel kurdum. Teklif, müşteri ve fatura kayıtlarını ilişkilendirip değişiklik geçmişi ekledim.', 'I built a role-based panel on Filament, linked quotes, customers and invoices, and added a change history.'),
                'lesson' => $t('İlk sürümde fazla rapor ekranı yaptım, ekip yalnızca ikisini kullandı. Artık raporları kullanıcılarla birlikte seçiyorum.', 'The first version shipped too many report screens and the team used only two. Now I choose reports together with users.'),
                'decisions' => [
                    ['q' => $t('Sıfırdan panel mi, Filament mı?', 'Custom panel or Filament?'), 'a' => $t('Filament: standart ekranlarda zaman kazandım, özel ihtiyaçlar için kendi bileşenlerimi yazdım.', 'Filament: standard screens came for free; I wrote custom components where needed.')],
                    ['q' => $t('Yetkiler nasıl yönetilsin?', 'How should permissions work?'), 'a' => $t('Policy ve Gate ile: her rolün yetkisi kodda, test edilebilir şekilde tanımlı.', "Policies and Gates: every role's permissions live in code and are tested.")],
                ],
                'tags' => ['Filament', 'Laravel', 'PostgreSQL'],
            ],
            [
                'slug' => 'siradaki', 'category' => 'web', 'year' => '2025',
                'type' => $t('SAAS'),
                'title' => $t('Sıradaki'),
                'description' => $t('Kuaför, klinik ve stüdyolar için online randevu ve abonelik sistemi: takvim, SMS hatırlatma ve iyzico ile ödeme.', 'Online booking and subscriptions for salons, clinics and studios: calendar, SMS reminders and iyzico payments.'),
                'result' => $t("Gelmeyen randevu oranı %22'den %7'ye indi", 'No-shows dropped from 22% to 7%'),
                'role' => $t('Full stack · ürün kararları dahil', 'Full stack, including product decisions'),
                'duration' => $t('4 ay + bakım', '4 months + ongoing care'),
                'problem' => $t('İşletmeler randevuları telefonla alıyor, hatırlatma yapılmadığı için her beş müşteriden biri gelmiyordu.', "Businesses took bookings by phone and, without reminders, one in five customers didn't show up."),
                'approach' => $t('Çok kiracılı bir SaaS kurdum. Her işletme kendi takvimini yönetiyor, hatırlatmalar zamanlanmış job ile otomatik gidiyor.', 'I built a multi-tenant SaaS: each business runs its own calendar and reminders go out automatically via scheduled jobs.'),
                'lesson' => $t('Çok kiracılı yapıyı baştan kurmak doğruydu, ama kiracıya özel özelleştirme isteklerini daha erken sınırlamalıydım.', 'Building multi-tenancy from day one was right, but I should have limited per-tenant customisation requests earlier.'),
                'decisions' => [
                    ['q' => $t('Her kiracıya ayrı veritabanı mı?', 'A database per tenant?'), 'a' => $t('Hayır: tek veritabanı ve tenant_id. Bakım ve migration çok daha kolay.', 'No: one database with tenant_id. Maintenance and migrations stay simple.')],
                    ['q' => $t('Hatırlatmalar nasıl gönderilsin?', 'How should reminders be sent?'), 'a' => $t('Zamanlanmış job + kuyruk: SMS sağlayıcısı hata verirse otomatik yeniden deneme.', 'Scheduled jobs + queue: automatic retries when the SMS provider fails.')],
                ],
                'tags' => ['Inertia', 'Vue 3', 'Tailwind', 'iyzico'],
            ],
            [
                'slug' => 'rota-mobil-api', 'category' => 'api', 'year' => '2024',
                'type' => $t('REST API'),
                'title' => $t('Rota Mobil API'),
                'description' => $t('Bir kurye uygulamasının iOS ve Android sürümlerinin kullandığı, Sanctum kimlik doğrulamalı, sürümlü REST API.', 'Versioned REST API with Sanctum auth, used by the iOS and Android versions of a courier app.'),
                'result' => $t("p95 yanıt süresi 820 ms'den 140 ms'ye indi", 'p95 response time dropped from 820 ms to 140 ms'),
                'role' => $t('Backend · API tasarımı', 'Backend · API design'),
                'duration' => $t('3 ay', '3 months'),
                'problem' => $t('Mobil ekip dokümansız ve tutarsız bir API ile çalışıyordu: aynı veri farklı uç noktalarda farklı biçimde dönüyor, hiç test yoktu.', 'The mobile team worked against an undocumented, inconsistent API: the same data came back in different shapes and there were no tests.'),
                'approach' => $t('Sürümlü (/v1), Resource sınıflarıyla tutarlı yanıt veren bir API tasarladım. Her endpoint Pest ile test edildi ve dokümante edildi.', 'I designed a versioned (/v1) API with consistent Resource responses. Every endpoint is tested with Pest and documented.'),
                'lesson' => $t('Sürümlemeyi ilk günden yapmasaydım geçiş çok daha sancılı olurdu. Dokümantasyonu koddan otomatik üretmek en iyi kararımdı.', 'Without versioning from day one the migration would have been far more painful. Generating docs from code was my best decision.'),
                'decisions' => [
                    ['q' => $t('Passport mu, Sanctum mu?', 'Passport or Sanctum?'), 'a' => $t('Sanctum: mobil token ihtiyacı için yeterli, daha sade.', 'Sanctum: enough for mobile tokens, and simpler.')],
                    ['q' => $t("Yoğun endpoint'ler nasıl hızlandırılsın?", 'How to speed up hot endpoints?'), 'a' => $t('Redis cache + rate limiting: tekrarlayan sorgular veritabanına gitmiyor.', 'Redis cache + rate limiting: repeated queries never hit the database.')],
                ],
                'tags' => ['Laravel', 'Sanctum', 'Redis', 'Pest'],
            ],
        ];

        // Demo görüntülenme sayıları (panel istatistiklerini görmek için)
        $views = ['atolye-market' => 1284, 'teklif-masasi' => 642, 'siradaki' => 931, 'rota-mobil-api' => 517];

        foreach ($projects as $i => $project) {
            $project['image'] = $this->demoImage("projects/{$project['slug']}.jpg");
            $project['gallery'] = array_values(array_filter([
                $this->demoImage("gallery/{$project['slug']}-1.jpg", 'projects/gallery'),
                $this->demoImage("gallery/{$project['slug']}-2.jpg", 'projects/gallery'),
            ]));
            $project['views'] = $views[$project['slug']] ?? 0;
            Project::updateOrCreate(['slug' => $project['slug']], $project + ['sort' => $i]);
        }
    }

    private function services(): void
    {
        $t = fn ($tr, $en = null) => $this->t($tr, $en);
        $rows = [
            ['app', $t('Laravel web uygulaması', 'Laravel web applications'), $t('Sıfırdan, ölçeklenebilir ve test edilmiş web uygulamaları. Kimlik doğrulama, roller, ödeme ve raporlama dahil.', 'Scalable, tested web apps from scratch, including authentication, roles, payments and reporting.'), ['Laravel 12', 'Livewire', 'Pest']],
            ['cart', $t('E-ticaret ve pazar yeri', 'E-commerce and marketplaces'), $t("Çok satıcılı yapı, stok, kampanya ve kargo. Türkiye'deki ödeme ve fatura altyapılarıyla entegrasyon.", 'Multi-vendor setups, stock, campaigns and shipping, integrated with Turkish payment and invoicing services.'), ['iyzico', 'e-Fatura', 'Kargo API']],
            ['panel', $t('Admin panel ve CRM', 'Admin panels and CRMs'), $t('Filament ile hızlı, rol bazlı yönetim panelleri. Raporlar, dışa aktarma ve değişiklik geçmişi.', 'Fast, role-based admin panels with Filament: reports, exports and change history.'), ['Filament', 'Policies', 'Excel']],
            ['api', $t('REST API ve mobil backend', 'REST APIs and mobile backends'), $t("Mobil uygulamalar ve üçüncü taraflar için sürümlü, dokümante edilmiş ve test edilmiş API'ler.", 'Versioned, documented and tested APIs for mobile apps and third parties.'), ['Sanctum', 'OpenAPI', 'Redis']],
            ['up', $t('Sürüm yükseltme ve bakım', 'Upgrades and maintenance'), $t('Eski Laravel sürümlerinden güncel sürüme kademeli ve testlerle güvenceye alınmış geçiş.', 'Step-by-step, test-backed upgrades from old Laravel versions to the current release.'), ['Laravel 8 → 12', 'PHP 8.3', 'CI']],
            ['gauge', $t('Performans ve kod incelemesi', 'Performance and code review'), $t('Yavaş sayfaların nedenini bulurum: N+1 sorgular, eksik indeksler, cache ve kuyruk fırsatları. Yazılı raporla.', 'I find why pages are slow (N+1 queries, missing indexes, caching and queue opportunities) and deliver a written report.'), ['Telescope', 'Debugbar', 'Pulse']],
        ];
        foreach ($rows as $i => [$icon, $title, $desc, $tags]) {
            Service::create(['icon' => $icon, 'title' => $title, 'description' => $desc, 'tags' => $tags, 'sort' => $i]);
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
        foreach ($rows as $i => [$name, $desc, $kind, $stars]) {
            Repository::create(['name' => $name, 'description' => $desc, 'kind' => $kind, 'stars' => $stars, 'forks' => intdiv($stars, 7), 'pushed_at' => now()->subDays([3, 12, 27][$i] ?? 30), 'sort' => $i]);
        }
    }

    private function experience(): void
    {
        $t = fn ($tr, $en = null) => $this->t($tr, $en);
        $rows = [
            [$t('2024 — Bugün', '2024 — Present'), 'Full Stack Laravel Developer', $t('Nexa Dijital · İzmir', 'Nexa Digital · İzmir'), $t('E-ticaret ve SaaS müşterileri için Laravel ve Livewire ile uçtan uca uygulamalar geliştiriyorum. API tasarımı, Filament panelleri ve GitHub Actions ile otomatik deploy süreçlerinden sorumluyum.', 'I build end-to-end Laravel and Livewire apps for e-commerce and SaaS clients, and own API design, Filament panels and automated deploys with GitHub Actions.'), ['Laravel', 'Livewire', 'Filament', 'Docker'], true],
            [$t('2022 — 2024'), 'Backend Developer', $t('Pazarium Teknoloji · Uzaktan', 'Pazarium Technology · Remote'), $t('Günde 15 binden fazla siparişi işleyen pazar yeri altyapısında REST API geliştirme, sorgu optimizasyonu ve kargo/ödeme entegrasyonları.', 'REST API development, query tuning and shipping/payment integrations for a marketplace handling 15,000+ orders a day.'), ['PHP', 'Laravel', 'MySQL', 'Redis'], false],
            [$t('2020 — 2022'), 'Freelance Web Developer', $t('Serbest', 'Independent'), $t('Küçük işletmeler için 20\'den fazla web sitesi, online mağaza ve yönetim paneli teslim ettim.', 'Delivered 20+ websites, online shops and admin panels for small businesses.'), ['Laravel', 'Blade', 'Tailwind'], false],
        ];
        foreach ($rows as $i => [$period, $role, $company, $desc, $tags, $current]) {
            Experience::create(compact('period', 'role', 'company', 'tags') + ['description' => $desc, 'is_current' => $current, 'sort' => $i]);
        }

        $edu = [
            [$t('Eğitim', 'Education'), $t('Bilgisayar Mühendisliği', 'Computer Engineering (BSc)'), $t('Dokuz Eylül Üniversitesi · 2020', 'Dokuz Eylül University · 2020')],
            [$t('Sertifika', 'Certificate'), $t('AWS Certified Cloud Practitioner'), $t('Amazon Web Services · 2023')],
            [$t('Topluluk', 'Community'), $t('“Filament ile 10 dakikada admin panel” sunumu', 'Talk: “An admin panel in 10 minutes with Filament”'), $t('Laravel İzmir Meetup · 2024')],
        ];
        foreach ($edu as $i => [$kind, $title, $subtitle]) {
            Education::create(compact('kind', 'title', 'subtitle') + ['sort' => $i]);
        }
    }

    private function pricing(): void
    {
        $t = fn ($tr, $en = null) => $this->t($tr, $en);
        $rows = [
            [
                'title' => $t('Proje bazlı', 'Project-based'), 'badge' => null,
                'description' => $t('Yeni bir web uygulaması, e-ticaret ya da admin paneli. Kapsam ve takvim baştan belli.', 'A new web app, shop or admin panel. Scope and timeline agreed up front.'),
                'price_label' => $t('Başlangıç', 'From'), 'price' => $t('₺45.000', '$1,500'),
                'items' => [$t('Analiz ve kapsam dokümanı', 'Discovery and scope document'), $t('Tasarımdan koda arayüz', 'Design-to-code interface'), $t('Testler ve otomatik deploy', 'Tests and automated deploys'), $t('Teslim sonrası 30 gün ücretsiz destek', '30 days of free support after launch')],
                'cta' => $t('Proje için görüşelim', 'Discuss a project'), 'contact_type' => 'web', 'is_featured' => false,
            ],
            [
                'title' => $t('Aylık bakım', 'Monthly care'), 'badge' => $t('Uzun vadeli', 'Long-term'),
                'description' => $t('Mevcut Laravel projen için güncellemeler, güvenlik yamaları ve küçük geliştirmeler.', 'Updates, security patches and small improvements for your existing Laravel app.'),
                'price_label' => $t('Aylık', 'Monthly'), 'price' => $t('₺8.500 / ay', '$290 / month'),
                'items' => [$t('Laravel ve paket güncellemeleri', 'Laravel and package updates'), $t('Güvenlik ve yedek kontrolü', 'Security and backup checks'), $t('Ayda 10 saat geliştirme', '10 development hours a month'), $t('Aylık kısa rapor', 'Short monthly report')],
                'cta' => $t('Bakım için görüşelim', 'Discuss maintenance'), 'contact_type' => 'care', 'is_featured' => true,
            ],
            [
                'title' => $t('Danışmanlık', 'Consulting'), 'badge' => null,
                'description' => $t('Kod incelemesi, mimari kararlar ya da performans sorunları için saatlik destek.', 'Hourly help with code reviews, architecture decisions or performance problems.'),
                'price_label' => $t('Saatlik', 'Hourly'), 'price' => $t('₺1.500 / saat', '$50 / hour'),
                'items' => [$t('Kod ve mimari incelemesi', 'Code and architecture review'), $t('Performans analizi', 'Performance analysis'), $t('Ekibe Laravel eşliği', 'Laravel pairing with your team'), $t('Yazılı öneri raporu', 'Written recommendations')],
                'cta' => $t('Danışmanlık al', 'Book consulting'), 'contact_type' => 'consult', 'is_featured' => false,
            ],
        ];
        foreach ($rows as $i => $row) {
            PricingPlan::create($row + ['sort' => $i]);
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
