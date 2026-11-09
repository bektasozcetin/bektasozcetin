<?php

namespace Tests\Feature;

use App\Mail\NewContactMessage;
use App\Models\ContactMessage;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Faq;
use App\Models\Project;
use App\Models\Repository;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\User;
use Database\Seeders\PortfolioSeeder;
use Database\Seeders\PostSeeder;
use App\Mail\NewMeeting;
use App\Models\Meeting;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::forget(Setting::CACHE_KEY);
        $this->seed([PortfolioSeeder::class, PostSeeder::class]);
    }

    public function test_public_pages_render_in_both_languages(): void
    {
        $this->get('/')->assertOk()->assertSee('Seçili')->assertSee('Birlikte çalıştığım markalar')->assertSee('storage/brands/pazarium.svg', false)->assertSee('hreflang="en"', false);
        $this->get('/en')->assertOk()->assertSee('Selected')->assertSee('Book a call');
        $this->get('/projeler/randevu-ajandam')->assertOk()->assertSee('Randevu Ajandam');
        $this->get('/en/projects/arac-servis')->assertOk()->assertSee('the slowest is 207 ms');
        $this->get('/kvkk')->assertOk();
        $this->get('/en/privacy')->assertOk();
        $this->get('/sitemap.xml')->assertOk()->assertSee('/en/projects/arac-servis');
        $this->get('/projeler/olmayan-proje')->assertNotFound();
    }

    public function test_unpublished_project_is_hidden(): void
    {
        Project::where('slug', 'arac-servis')->update(['is_published' => false]);

        $this->get('/')->assertDontSee('projeler/arac-servis', false)->assertSee('projeler/randevu-ajandam', false);
        $this->get('/projeler/arac-servis')->assertNotFound();
    }

    public function test_contact_form_stores_message_and_notifies(): void
    {
        Mail::fake();

        $this->postJson('/iletisim', [
            'name' => 'Deneme', 'email' => 'deneme@example.test', 'project_type' => 'shop',
            'message' => 'Bir e-ticaret sitesi istiyorum.', 'kvkk' => true,
        ])->assertOk();

        $this->assertDatabaseHas('contact_messages', ['email' => 'deneme@example.test', 'locale' => 'tr']);
        Mail::assertSent(NewContactMessage::class);
    }

    public function test_contact_form_validates_and_blocks_bots(): void
    {
        $this->postJson('/en/contact', [])->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'message', 'kvkk']);

        $this->postJson('/iletisim', [
            'name' => 'Bot', 'email' => 'bot@example.test', 'message' => 'spam spam spam', 'kvkk' => true, 'website' => 'http://spam',
        ])->assertUnprocessable()->assertJsonValidationErrors(['website']);

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_admin_panel_pages_render(): void
    {
        $admin = User::factory()->create(['email' => 'admin@example.test']);
        config(['site.admin_email' => 'admin@example.test']);
        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden(); // başkası giremez
        auth()->logout();
        ContactMessage::create(['name' => 'A', 'email' => 'a@example.test', 'message' => 'Merhaba dünya!']);

        $this->get('/admin')->assertRedirect();
        $this->actingAs($admin);

        $this->get('/admin')->assertOk();
        foreach (['brands', 'projects', 'services', 'repositories', 'experiences', 'educations', 'testimonials', 'faqs', 'contact-messages', 'posts', 'meetings', 'ayarlar', 'skills'] as $resource) {
            $this->get("/admin/{$resource}")->assertOk();
        }
        $models = [
            'projects' => Project::class, 'services' => Service::class, 'repositories' => Repository::class,
            'experiences' => Experience::class, 'educations' => Education::class,
            'testimonials' => Testimonial::class, 'faqs' => Faq::class, 'posts' => Post::class,
        ];
        foreach ($models as $resource => $model) {
            $this->get("/admin/{$resource}/create")->assertOk();
            $this->get("/admin/{$resource}/{$model::first()->getRouteKey()}/edit")->assertOk()->assertSee('Türkçe');
        }
    }

    public function test_notes_pages_feed_and_og_image(): void
    {
        $this->get('/')->assertOk()->assertSee('Laravel üzerine')->assertSee('/notlar/laravel-n-plus-1-sorgulari', false)->assertSee('storage/og/', false);
        $this->get('/notlar')->assertOk()->assertSee('Ödemeleri neden kuyruğa alıyorum?');
        $this->get('/notlar?etiket=Filament')->assertOk()->assertSee('Filament ne zaman yeterli')->assertDontSee('Ödemeleri neden kuyruğa');
        $this->get('/notlar/laravel-n-plus-1-sorgulari')->assertOk()->assertSee('preventLazyLoading')->assertSee('BlogPosting', false);
        $this->get('/en/notes/laravel-n-plus-1-sorgulari')->assertOk()->assertSee('Actually getting rid of N+1');
        $this->get('/feed.xml')->assertOk()->assertSee('<rss', false);
        $this->get('/sitemap.xml')->assertSee('/en/notes/odemeleri-neden-kuyruga-aliyorum');

        // Taslak ve ileri tarihli notlar görünmez; ham HTML temizlenir
        Post::create(['slug' => 'taslak', 'title' => ['tr' => 'Gizli taslak'], 'excerpt' => ['tr' => 'x'], 'body' => ['tr' => 'Merhaba <script>alert(1)</script>']]);
        Post::create(['slug' => 'yarin', 'title' => ['tr' => 'Yarının notu'], 'excerpt' => ['tr' => 'x'], 'body' => ['tr' => 'x'], 'published_at' => now()->addDay()]);
        $this->get('/notlar/taslak')->assertNotFound();
        $this->get('/notlar/yarin')->assertNotFound();
        $this->get('/notlar')->assertDontSee('Gizli taslak')->assertDontSee('Yarının notu');
        $this->assertStringNotContainsString('<script>', Post::where('slug', 'taslak')->first()->html());
    }

    public function test_project_gallery_next_and_view_counter(): void
    {
        $project = Project::where('slug', 'randevu-ajandam')->first();
        $before = $project->views;

        $this->withHeader('User-Agent', 'Mozilla/5.0')->get('/projeler/randevu-ajandam')
            ->assertOk()->assertSee('projects/gallery/randevu-ajandam-2.jpg', false)->assertSee('Sonraki proje')->assertSee('Araç Servis Yönetim Sistemi');
        $this->withHeader('User-Agent', 'Mozilla/5.0')->get('/projeler/randevu-ajandam'); // aynı oturum: tekrar sayılmaz
        $this->assertSame($before + 1, $project->fresh()->views);

        $arac = Project::where('slug', 'arac-servis')->first();
        $this->withHeader('User-Agent', 'Googlebot/2.1')->post('/goruntulenme/arac-servis', [], ['X-CSRF-TOKEN' => csrf_token()]);
        $this->assertSame($arac->views, Project::where('slug', 'arac-servis')->first()->views); // botlar sayılmaz
    }

    public function test_cv_link_uses_uploaded_files(): void
    {
        $this->get('/')->assertSee('storage/cv/bektas-ozcetin-cv-tr.pdf', false);
        $this->get('/en')->assertSee('storage/cv/bektas-ozcetin-cv-en.pdf', false);
        Setting::put(['cv_tr' => null, 'cv_en' => null]);
        $this->get('/')->assertDontSee('storage/cv/', false);
    }

    public function test_booking_calendar_flow(): void
    {
        Mail::fake();
        $this->travelTo(now('Europe/Istanbul')->next('Monday')->setTime(8, 0));
        Meeting::query()->delete();

        $days = $this->getJson('/gorusme/uygunluk')->assertOk()->json('days');
        $this->assertNotEmpty($days);
        $date = $days[1]['date'];
        $time = $days[1]['times'][0][0];

        $payload = ['date' => $date, 'time' => $time, 'name' => 'Ada', 'email' => 'ada@example.test', 'topic' => 'web', 'kvkk' => true];
        $this->postJson('/gorusme', $payload)->assertOk();
        $this->assertDatabaseHas('meetings', ['name' => 'Ada', 'status' => 'pending']);
        Mail::assertSent(NewMeeting::class);

        // Aynı saat artık dolu
        $this->postJson('/gorusme', ['name' => 'Bora', 'email' => 'bora@example.test'] + $payload)->assertStatus(422)->assertJsonValidationErrors('slot');
        $slot = collect($this->getJson('/gorusme/uygunluk')->json('days'))->firstWhere('date', $date)['times'][0];
        $this->assertSame([$time, false], $slot);

        $this->travel(2)->minutes(); // dakikada 3 istek sınırını aş
        // Takvimde olmayan saat, eksik onay ve bot reddedilir
        $this->postJson('/en/meeting', ['time' => '03:00'] + $payload)->assertStatus(422)->assertJsonValidationErrors('slot');
        $this->postJson('/gorusme', ['kvkk' => false, 'time' => $days[1]['times'][1][0]] + $payload)->assertStatus(422)->assertJsonValidationErrors('kvkk');
        $this->postJson('/gorusme', ['website' => 'spam', 'time' => $days[1]['times'][1][0]] + $payload)->assertStatus(422);

        // Takvim kapatılınca uç noktalar kapanır, butonlar forma gider
        Setting::put(['booking_enabled' => false]);
        $this->getJson('/gorusme/uygunluk')->assertNotFound();
        $this->get('/')->assertDontSee('bookingForm', false);
    }

    public function test_custom_login_page_renders_and_authenticates(): void
    {
        $this->get('/admin/login')->assertOk()->assertSee('Tekrar')->assertSee('bk-auth', false)->assertSee('admin-login', false);

        User::factory()->create(['email' => 'admin@example.test', 'password' => 'dogru-sifre']);
        config(['site.admin_email' => 'admin@example.test']);

        \Livewire\Livewire::test(\App\Filament\Pages\Auth\Login::class)
            ->set('data.email', 'admin@example.test')->set('data.password', 'yanlis')
            ->call('authenticate')->assertHasErrors('data.email');
        $this->assertGuest();

        \Livewire\Livewire::test(\App\Filament\Pages\Auth\Login::class)
            ->set('data.email', 'admin@example.test')->set('data.password', 'dogru-sifre')
            ->call('authenticate')->assertRedirect('/admin');
        $this->assertAuthenticated();
    }

    public function test_skills_stack_on_skills_page(): void
    {
        $this->get('/yetkinlikler')->assertOk()->assertSee('Teknoloji')->assertSee('PHP 8.3')->assertSee('aria-valuenow="95"', false);
        \App\Models\Skill::where('name', 'Figma')->update(['is_published' => false]);
        $this->get('/yetkinlikler')->assertDontSee('Figma');
        $this->get('/en/skills')->assertSee('Tech')->assertSee('Core expertise');
    }

    public function test_split_pages_render_in_both_languages(): void
    {
        $sayfalar = [
            'hizmetler' => 'services', 'yetkinlikler' => 'skills', 'hakkimda' => 'about',
            'deneyim' => 'experience', 'referanslar' => 'testimonials', 'sss' => 'faq',
        ];

        foreach ($sayfalar as $tr => $en) {
            $this->get('/'.$tr)->assertOk()
                ->assertSee(__('site.pages.'.$en.'.title'))  // <title> ve sekme adı
                ->assertSee('<h1', false)                    // her sayfada tek başlık
                ->assertSee('/en/'.$en, false);              // hreflang karşılığı
            $this->get('/en/'.$en)->assertOk();
        }

        // Ana sayfa artık yalnızca vitrin: derin bölümler kendi adreslerinde.
        $this->get('/')->assertOk()
            ->assertSee('id="kesfet"', false)
            ->assertDontSee('id="yetkinlikler"', false)
            ->assertDontSee('id="deneyim"', false);

        // Fiyatlandırma tamamen kaldırıldı: ne sayfa, ne panel, ne tablo.
        $this->get('/fiyatlar')->assertNotFound();
        $this->get('/en/pricing')->assertNotFound();
        $this->get('/admin/pricing-plans')->assertNotFound();
        $this->assertFalse(\Illuminate\Support\Facades\Schema::hasTable('pricing_plans'));

        // Site haritası yeni sayfaları içeriyor.
        $this->get('/sitemap.xml')->assertOk()->assertSee('/hizmetler')->assertSee('/en/faq');
    }

    public function test_structured_data_is_valid_json(): void
    {
        // Blade'in @context direktifi JSON-LD'yi bozuyordu; şemalar artık
        // App\Support\Schema içinde üretiliyor. Bu test o hatayı tekrar yakalar.
        $cikar = function (string $html): array {
            preg_match_all('~<script type="application/ld\+json">(.*?)</script>~s', $html, $m);

            return array_map(fn ($json) => json_decode(trim($json), true), $m[1]);
        };

        $anaSayfa = $cikar($this->get('/')->assertOk()->getContent());
        $this->assertCount(1, $anaSayfa);
        $this->assertSame('https://schema.org', $anaSayfa[0]['@context'] ?? null);
        $this->assertSame('Person', $anaSayfa[0]['@type'] ?? null);
        $this->assertSame('Ankara', $anaSayfa[0]['address']['addressLocality'] ?? null);

        $sss = $cikar($this->get('/sss')->assertOk()->getContent());
        $turler = array_column(array_filter($sss), '@type');
        $this->assertContains('FAQPage', $turler);

        $post = \App\Models\Post::published()->first();
        $yazi = $cikar($this->get('/notlar/'.$post->slug)->assertOk()->getContent());
        $turler = array_column(array_filter($yazi), '@type');
        $this->assertContains('BlogPosting', $turler);

        // Hiçbirinde ham PHP sızıntısı olmamalı
        foreach ([...$anaSayfa, ...$sss, ...$yazi] as $sema) {
            $this->assertIsArray($sema, 'JSON-LD çözümlenemedi: bozuk çıktı');
        }
    }

    public function test_robots_txt_points_to_absolute_sitemap(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Disallow: /admin')
            ->assertSee(route('sitemap'));   // göreli değil, mutlak adres
    }

    public function test_github_sync_updates_and_adds_repositories(): void
    {
        \Illuminate\Support\Facades\Http::fake([
            'api.github.com/users/ornek/repos*' => \Illuminate\Support\Facades\Http::response([
                ['id' => 1, 'full_name' => 'bektasozcetin/laravel-iyzico', 'html_url' => 'https://github.com/bektasozcetin/laravel-iyzico', 'description' => 'GitHub açıklaması', 'language' => 'PHP', 'stargazers_count' => 300, 'forks_count' => 41, 'topics' => ['laravel'], 'pushed_at' => '2026-11-01T10:00:00Z', 'fork' => false, 'archived' => false, 'private' => false],
                ['id' => 2, 'full_name' => 'bektasozcetin/yeni-paket', 'html_url' => 'https://github.com/bektasozcetin/yeni-paket', 'description' => 'Yeni bir paket', 'language' => 'Blade', 'stargazers_count' => 3, 'forks_count' => 0, 'topics' => [], 'pushed_at' => '2026-11-05T10:00:00Z', 'fork' => false, 'archived' => false, 'private' => false],
                ['id' => 3, 'full_name' => 'bektasozcetin/fork-edilmis', 'html_url' => 'https://github.com/x', 'description' => null, 'language' => null, 'stargazers_count' => 0, 'forks_count' => 0, 'pushed_at' => null, 'fork' => true, 'archived' => false, 'private' => false],
            ]),
        ]);
        Setting::put(['github_username' => 'ornek']);

        // Seeder'daki kurgusal repolar gizli gelir. Panelden yayına alınmış bir
        // repoyu taklit et: eşitleme bu bayrağa dokunmamalı.
        \App\Models\Repository::where('name', 'bektasozcetin/laravel-iyzico')->update(['is_published' => true]);

        $result = \App\Support\GitHubSync::run();
        $this->assertSame(['created' => 1, 'updated' => 1, 'skipped' => 1], $result);

        $existing = \App\Models\Repository::where('name', 'bektasozcetin/laravel-iyzico')->first();
        $this->assertSame(300, $existing->stars);
        $this->assertSame(41, $existing->forks);
        $this->assertStringContainsString('iyzico', $existing->description['tr']); // elle yazılan açıklama korunur
        $this->assertTrue($existing->is_published);

        $new = \App\Models\Repository::where('name', 'bektasozcetin/yeni-paket')->first();
        $this->assertFalse($new->is_published); // yeni repolar gizli gelir
        $this->get('/')->assertSee('300')->assertDontSee('bektasozcetin/yeni-paket');

        Setting::put(['github_username' => null]);
        config(['site.social.GitHub' => null]);
        $this->expectException(\RuntimeException::class);
        \App\Support\GitHubSync::run();
    }
}
