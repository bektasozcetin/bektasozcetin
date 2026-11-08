<?php

namespace Tests\Feature;

use App\Mail\NewContactMessage;
use App\Models\ContactMessage;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Faq;
use App\Models\PricingPlan;
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
        $this->get('/projeler/atolye-market')->assertOk()->assertSee('Atölye Market');
        $this->get('/en/projects/atolye-market')->assertOk()->assertSee('Checkout abandonment');
        $this->get('/kvkk')->assertOk();
        $this->get('/en/privacy')->assertOk();
        $this->get('/sitemap.xml')->assertOk()->assertSee('/en/projects/siradaki');
        $this->get('/projeler/olmayan-proje')->assertNotFound();
    }

    public function test_unpublished_project_is_hidden(): void
    {
        Project::where('slug', 'siradaki')->update(['is_published' => false]);

        $this->get('/')->assertDontSee('projeler/siradaki', false)->assertSee('projeler/atolye-market', false);
        $this->get('/projeler/siradaki')->assertNotFound();
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
        foreach (['brands', 'projects', 'services', 'repositories', 'experiences', 'educations', 'pricing-plans', 'testimonials', 'faqs', 'contact-messages', 'posts', 'meetings', 'ayarlar'] as $resource) {
            $this->get("/admin/{$resource}")->assertOk();
        }
        $models = [
            'projects' => Project::class, 'services' => Service::class, 'repositories' => Repository::class,
            'experiences' => Experience::class, 'educations' => Education::class, 'pricing-plans' => PricingPlan::class,
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
        $project = Project::where('slug', 'atolye-market')->first();
        $before = $project->views;

        $this->withHeader('User-Agent', 'Mozilla/5.0')->get('/projeler/atolye-market')
            ->assertOk()->assertSee('projects/gallery/atolye-market-2.jpg', false)->assertSee('Sonraki proje')->assertSee('Teklif Masası');
        $this->withHeader('User-Agent', 'Mozilla/5.0')->get('/projeler/atolye-market'); // aynı oturum: tekrar sayılmaz
        $this->assertSame($before + 1, $project->fresh()->views);

        $this->withHeader('User-Agent', 'Googlebot/2.1')->post('/goruntulenme/teklif-masasi', [], ['X-CSRF-TOKEN' => csrf_token()]);
        $this->assertSame(642, Project::where('slug', 'teklif-masasi')->first()->views); // botlar sayılmaz
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
}
