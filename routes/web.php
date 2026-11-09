<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ViewController;
use Illuminate\Support\Facades\Route;

/*
| Türkçe:    /     /projeler/{slug}     /notlar(/{slug})  /kvkk        POST /iletisim  POST /gorusme
| İngilizce: /en   /en/projects/{slug}  /en/notes(/{slug}) /en/privacy  POST /en/contact POST /en/meeting
*/
$paths = [
    'tr' => ['locale' => 'tr', 'prefix' => '', 'name' => '', 'project' => 'projeler', 'notes' => 'notlar', 'privacy' => 'kvkk', 'contact' => 'iletisim', 'meeting' => 'gorusme'],
    'en' => ['locale' => 'en', 'prefix' => 'en', 'name' => 'en.', 'project' => 'projects', 'notes' => 'notes', 'privacy' => 'privacy', 'contact' => 'contact', 'meeting' => 'meeting'],
];

foreach ($paths as $locale => $p) {
    Route::prefix($p['prefix'])
        ->name($p['name'])
        ->middleware('locale:'.$locale)
        ->group(function () use ($p) {
            Route::get('/', HomeController::class)->name('home');

            // Alt sayfalar: config/nav.php tek doğruluk kaynağı.
            foreach (config('nav.pages') as $page => $cfg) {
                // Sayfa adı rota adından okunuyor; route defaults kullanılsaydı
                // route() üretilen adreslere ?page=... diye eklerdi.
                Route::get($cfg['slug'][$p['locale']], [PageController::class, 'show'])->name($page);
            }

            Route::get($p['project'].'/{project:slug}', ProjectController::class)->name('projects.show');
            Route::get($p['notes'], [PostController::class, 'index'])->name('posts.index');
            Route::get($p['notes'].'/{post:slug}', [PostController::class, 'show'])->name('posts.show');
            Route::view($p['privacy'], 'privacy')->name('privacy');
            Route::post($p['contact'], ContactController::class)->middleware('throttle:contact')->name('contact');
            Route::post($p['meeting'], [MeetingController::class, 'store'])->middleware('throttle:contact')->name('meetings.store');
        });
}

// Dilden bağımsız uç noktalar
Route::get('gorusme/uygunluk', [MeetingController::class, 'slots'])->middleware('throttle:60,1')->name('meetings.slots');
Route::post('goruntulenme/{project:slug}', ViewController::class)->middleware('throttle:60,1')->name('views.project');
Route::get('sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('feed.xml', FeedController::class)->name('feed');

// robots.txt: site haritasi mutlak adres olmali, bu yuzden statik dosya degil rota.
Route::get('robots.txt', fn () => response(
    "User-agent: *
Disallow: /admin
Disallow: /gorusme/

Sitemap: ".route('sitemap')."
",
    200,
    ['Content-Type' => 'text/plain; charset=UTF-8'],
))->name('robots');
