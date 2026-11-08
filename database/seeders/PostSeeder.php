<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

/*
| Demo notlar (blog). Yayından önce panelden (/admin → Notlar) kendi yazılarınla değiştir.
*/
class PostSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->posts() as $post) {
            Post::updateOrCreate(['slug' => $post['slug']], $post);
        }
    }

    private function posts(): array
    {
        return [
            [
                'slug' => 'laravel-n-plus-1-sorgulari',
                'published_at' => '2026-10-28 09:30:00',
                'tags' => ['Laravel', 'Performans', 'Eloquent'],
                'views' => 412,
                'title' => [
                    'tr' => "Laravel'de N+1 sorgularını gerçekten bitirmek",
                    'en' => 'Actually getting rid of N+1 queries in Laravel',
                ],
                'excerpt' => [
                    'tr' => 'Bir sipariş listesi 214 sorguyla açılıyordu. with() eklemek işin yarısıydı; kalan yarısı, sorunun bir daha geri gelmemesini sağlamaktı.',
                    'en' => 'An order list was loading with 214 queries. Adding with() was half the job; the other half was making sure the problem never came back.',
                ],
                'body' => [
                    'tr' => <<<'MD'
Atölye Market'te satıcı panelindeki sipariş listesi mobilde 4 saniyede açılıyordu. Debugbar'ı açtığımda sayfa başına **214 sorgu** gördüm. Klasik N+1: her sipariş için müşteri, her müşteri için adres, her sipariş kalemi için ürün ayrı ayrı çekiliyordu.

## 1. Önce sorunu görünür yap

Geliştirme ortamında tembel yüklemeyi tamamen yasaklıyorum. Böylece N+1 bir uyarı değil, hata olarak karşıma çıkıyor:

```php
// AppServiceProvider::boot()
Model::preventLazyLoading(! app()->isProduction());
```

Canlıda ise hata fırlatmak yerine loglamak daha güvenli. Kullanıcı bozuk sayfa görmez, ben de hangi sayfanın sorunlu olduğunu öğrenirim.

## 2. İhtiyacın olanı tek seferde yükle

```php
$orders = Order::query()
    ->with(['customer:id,name', 'items.product:id,name,price'])
    ->withCount('items')
    ->latest()
    ->paginate(25);
```

Burada iki küçük detay önemli:

- `customer:id,name` ile yalnızca gereken sütunları seçmek bellek kullanımını belirgin şekilde düşürüyor.
- Kalem sayısını göstermek için `items` ilişkisini yüklemek yerine `withCount` kullanmak tek bir alt sorguyla iş görüyor.

## 3. Blade'de gizli sorgulara dikkat

En sinsi N+1'ler view içinde saklanır. `$order->customer->orders->count()` gibi bir satır, ilişki yüklenmediyse her satırda yeni bir sorgu demek. Sayıyı controller'da `withCount` ile hazırlayıp view'a düz bir değer olarak vermek hem hızlı hem okunaklı.

## 4. Testle kilitle

Düzelttiğim sorunun geri gelmemesi için sayfanın sorgu sayısını test ediyorum:

```php
it('lists orders without N+1', function () {
    Order::factory()->count(30)->hasItems(3)->create();

    DB::enableQueryLog();
    $this->get('/panel/siparisler')->assertOk();

    expect(count(DB::getQueryLog()))->toBeLessThan(10);
});
```

## Sonuç

Sipariş listesi 214 sorgudan **6 sorguya**, açılış süresi 4 saniyeden 380 milisaniyeye indi. Asıl kazanç ise `preventLazyLoading` ve bu küçük test oldu: o günden beri aynı sorun bir kez bile geri gelmedi.

> Performans sorunu çoğu zaman tek bir yavaş sorgu değil, yüzlerce hızlı sorgunun toplamıdır.
MD,
                    'en' => <<<'MD'
At Atölye Market, the order list in the seller panel took 4 seconds to load on mobile. When I opened Debugbar I saw **214 queries** per page. Classic N+1: the customer for every order, the address for every customer and the product for every line item were all fetched one by one.

## 1. Make the problem visible first

In development I forbid lazy loading entirely, so an N+1 shows up as an error instead of a warning:

```php
// AppServiceProvider::boot()
Model::preventLazyLoading(! app()->isProduction());
```

In production it's safer to log instead of throwing. Users never see a broken page, and I still learn which page is the culprit.

## 2. Load what you need in one go

```php
$orders = Order::query()
    ->with(['customer:id,name', 'items.product:id,name,price'])
    ->withCount('items')
    ->latest()
    ->paginate(25);
```

Two small details matter here:

- Selecting only the needed columns with `customer:id,name` noticeably reduces memory use.
- To show how many items an order has, `withCount` does the job with a single subquery instead of loading the whole `items` relation.

## 3. Watch out for hidden queries in Blade

The sneakiest N+1s hide in views. A line like `$order->customer->orders->count()` means a new query on every row if the relation isn't loaded. Preparing the number in the controller with `withCount` and passing a plain value to the view is both faster and easier to read.

## 4. Lock it in with a test

To make sure the fix doesn't regress, I test the page's query count:

```php
it('lists orders without N+1', function () {
    Order::factory()->count(30)->hasItems(3)->create();

    DB::enableQueryLog();
    $this->get('/panel/orders')->assertOk();

    expect(count(DB::getQueryLog()))->toBeLessThan(10);
});
```

## The result

The order list went from 214 queries to **6**, and load time from 4 seconds to 380 milliseconds. The real win, though, was `preventLazyLoading` and that small test: the problem hasn't come back once since.

> A performance problem is rarely one slow query. It's usually hundreds of fast ones added together.
MD,
                ],
            ],
            [
                'slug' => 'odemeleri-neden-kuyruga-aliyorum',
                'published_at' => '2026-10-09 10:00:00',
                'tags' => ['Laravel', 'Kuyruk', 'iyzico'],
                'views' => 287,
                'title' => [
                    'tr' => 'Ödemeleri neden kuyruğa alıyorum?',
                    'en' => 'Why I put payments on a queue',
                ],
                'excerpt' => [
                    'tr' => 'Ödeme sağlayıcısı yavaşladığında bütün sipariş akışınız kilitlenmemeli. Horizon, idempotency anahtarları ve yeniden deneme stratejisiyle kurduğum yapı.',
                    'en' => "A slow payment provider shouldn't freeze your whole order flow. The setup I use with Horizon, idempotency keys and a retry strategy.",
                ],
                'body' => [
                    'tr' => <<<'MD'
Kasım indirimlerinin ilk akşamı ödeme sağlayıcısının yanıt süresi 300 milisaniyeden 9 saniyeye çıktı. Ödeme isteği controller içinde senkron yapıldığı için PHP işçileri tek tek doldu ve site, ödeme yapmayan ziyaretçiler için bile yanıt vermez hale geldi.

O geceden sonra kuralım net: **dış servise giden hiçbir istek kullanıcının isteğini beklemez.**

## Akış nasıl değişti?

1. Kullanıcı "Öde" dediğinde sipariş `payment_pending` durumuyla kaydedilir.
2. `ChargeOrder` job'u kuyruğa atılır ve kullanıcı anında bir bekleme ekranına yönlenir.
3. Job ödemeyi dener; sonuç gelince sipariş güncellenir ve ekran kendini yeniler.

```php
ChargeOrder::dispatch($order)->onQueue('payments');
```

## Aynı ödeme iki kez alınmasın

Kuyruklar "en az bir kez" çalışır. Yani bir job, nadiren de olsa iki kez çalışabilir. Bu yüzden her ödeme isteğine siparişe bağlı sabit bir anahtar veriyorum:

```php
$response = $gateway->charge(
    amount: $order->total,
    conversationId: 'order-'.$order->id,
);
```

Sağlayıcı aynı anahtarla gelen ikinci isteği yeni bir çekim olarak değil, ilkinin tekrarı olarak görür. Ayrıca job içinde, ödemeye geçmeden önce siparişin hâlâ `payment_pending` olduğunu kontrol ediyorum.

## Yeniden deneme stratejisi

```php
public $tries = 5;

public function backoff(): array
{
    return [10, 30, 90, 300];
}
```

Ağ hatası ya da zaman aşımı olursa job artan aralıklarla yeniden denenir. Kart reddi gibi kalıcı hatalarda ise denemeyi hemen bırakıp kullanıcıya anlaşılır bir mesaj gösteriyorum. Her hatayı yeniden denemek, reddedilen kartı beş kez çekmeye çalışmak demek.

## Horizon ile görünürlük

`payments` kuyruğunu ayrı bir süreçte, diğer işlerden bağımsız çalıştırıyorum. E-posta gönderimi birikse bile ödemeler beklemiyor. Horizon panelinde başarısız job'ları tek tıkla yeniden deneyebiliyorum.

## Sonuç

Bir sonraki kampanya gecesinde sağlayıcı yine yavaşladı. Bu kez site ayaktaydı, ödemeler birkaç saniye gecikmeyle tamamlandı ve **tek bir çift çekim yaşanmadı.**
MD,
                    'en' => <<<'MD'
On the first evening of the November sales, the payment provider's response time went from 300 milliseconds to 9 seconds. Because the charge happened synchronously inside the controller, PHP workers filled up one by one and the site stopped responding, even for visitors who weren't paying.

Since that night my rule is simple: **no request to an external service makes the user's request wait.**

## How the flow changed

1. When the user clicks "Pay", the order is saved with a `payment_pending` status.
2. A `ChargeOrder` job is queued and the user is sent straight to a waiting screen.
3. The job attempts the charge; when the result arrives, the order is updated and the screen refreshes itself.

```php
ChargeOrder::dispatch($order)->onQueue('payments');
```

## Never charge twice

Queues run jobs "at least once", so a job can occasionally run twice. That's why every charge request gets a fixed key tied to the order:

```php
$response = $gateway->charge(
    amount: $order->total,
    conversationId: 'order-'.$order->id,
);
```

The provider treats a second request with the same key as a repeat of the first, not as a new charge. Inside the job I also check that the order is still `payment_pending` before charging.

## Retry strategy

```php
public $tries = 5;

public function backoff(): array
{
    return [10, 30, 90, 300];
}
```

Network errors and timeouts are retried at growing intervals. Permanent failures like a declined card stop immediately and show the user a clear message. Retrying every error would mean trying to charge a declined card five times.

## Visibility with Horizon

The `payments` queue runs in its own process, independent of everything else. Even if emails pile up, payments don't wait. In Horizon I can retry failed jobs with a single click.

## The result

On the next campaign night the provider slowed down again. This time the site stayed up, payments completed with a few seconds' delay and **not a single double charge happened.**
MD,
                ],
            ],
            [
                'slug' => 'laravel-8den-12ye-yukseltme',
                'published_at' => '2026-09-16 09:00:00',
                'tags' => ['Laravel', 'Bakım', 'Yükseltme'],
                'views' => 356,
                'title' => [
                    'tr' => "Laravel 8'den 12'ye: kademeli yükseltme kontrol listesi",
                    'en' => 'From Laravel 8 to 12: a step-by-step upgrade checklist',
                ],
                'excerpt' => [
                    'tr' => 'Dört yıldır güncellenmeyen bir projeyi tek seferde değil, dört küçük adımda yükselttim. Kullandığım sıra ve her adımda kontrol ettiğim şeyler.',
                    'en' => "I upgraded a project that hadn't been touched in four years in four small steps instead of one big jump. The order I followed and what I checked at each step.",
                ],
                'body' => [
                    'tr' => <<<'MD'
Bir müşterinin ürün yönetim sistemi hâlâ Laravel 8 ve PHP 7.4 üzerinde çalışıyordu. Barındırma firması PHP 7.4 desteğini kesmek üzereydi; yani yükseltme bir tercih değil, zorunluluktu.

Doğrudan 12'ye atlamak cazip görünüyor ama kırılan şeylerin nedenini bulmayı neredeyse imkânsız hale getiriyor. Ben her seferinde **bir ana sürüm** ilerledim.

## Başlamadan önce

- **Test yoksa önce test yaz.** Her şeyi kapsaması gerekmiyor; giriş, sipariş oluşturma ve ödeme gibi kritik akışlar için 15–20 uçtan uca test bile büyük güvence.
- `composer outdated -D` ile doğrudan bağımlılıkları listele. Bakımı bırakılmış paketleri en başta tespit etmek sürprizleri azaltır.
- Yükseltmeyi ayrı bir dalda yap ve her adımı ayrı bir commit olarak tut.

## Her sürümde aynı döngü

1. `composer.json` içinde `laravel/framework` ve ilgili paketlerin sürümünü bir üst sürüme çek.
2. Resmî yükseltme rehberindeki "yüksek etki" maddelerini tek tek uygula.
3. Testleri çalıştır, kırılanları düzelt.
4. Staging ortamına al ve kritik akışları elle de dene.

## Bu projede en çok uğraştıranlar

- **PHP 8.1 geçişi:** `null` alan bazı yerleşik fonksiyonlar uyarı vermeye başladı. Statik analiz (Larastan, seviye 5) bunların çoğunu tek seferde buldu.
- **Laravel 10:** Kendi yazdığımız birkaç doğrulama kuralı yeni arayüze taşınmalıydı.
- **Laravel 11 iskeleti:** Yeni ve sade dosya yapısına geçmek zorunlu değil. Eski yapıyı koruyup yalnızca gerekli değişiklikleri yaptım, geçişi sonraya bıraktım.

## CI ile güvenceye al

```yaml
- name: Testler
  run: php artisan test --parallel
- name: Statik analiz
  run: vendor/bin/phpstan analyse
```

Her commit'te testlerin ve statik analizin çalışması, bir sonraki sürüme geçmeden önce zeminin sağlam olduğunu gösteriyor.

## Sonuç

Yükseltme dört haftada, kullanıcılar tek bir kesinti fark etmeden tamamlandı. PHP 8.3 ve Laravel 12 ile sayfa yanıt süreleri ortalama %22 iyileşti. Projede artık 140 test var ve sonraki yükseltme birkaç saatlik bir iş.
MD,
                    'en' => <<<'MD'
A client's product management system was still running on Laravel 8 and PHP 7.4. The host was about to drop PHP 7.4 support, so upgrading wasn't optional.

Jumping straight to 12 is tempting, but it makes it nearly impossible to tell why things broke. I moved **one major version** at a time.

## Before you start

- **No tests? Write some first.** They don't need to cover everything; even 15–20 end-to-end tests for critical flows like login, placing an order and payment give you a lot of confidence.
- List direct dependencies with `composer outdated -D`. Spotting abandoned packages early avoids surprises.
- Do the upgrade on a separate branch and keep each step in its own commit.

## The same loop for every version

1. Bump `laravel/framework` and related packages to the next major version in `composer.json`.
2. Work through the "high impact" items in the official upgrade guide one by one.
3. Run the tests and fix what breaks.
4. Deploy to staging and try the critical flows by hand too.

## What took the most effort here

- **The PHP 8.1 move:** some built-in functions started warning when given `null`. Static analysis (Larastan, level 5) found most of them in one pass.
- **Laravel 10:** a few custom validation rules had to move to the new interface.
- **The Laravel 11 skeleton:** switching to the new slim structure isn't required. I kept the old structure, made only the necessary changes and left that move for later.

## Guard it with CI

```yaml
- name: Tests
  run: php artisan test --parallel
- name: Static analysis
  run: vendor/bin/phpstan analyse
```

Running tests and static analysis on every commit shows the ground is solid before moving to the next version.

## The result

The upgrade took four weeks, and users never noticed a single outage. With PHP 8.3 and Laravel 12, page response times improved by 22% on average. The project now has 140 tests, and the next upgrade is a matter of hours.
MD,
                ],
            ],
            [
                'slug' => 'filament-ne-zaman-yeterli',
                'published_at' => '2026-08-21 11:00:00',
                'tags' => ['Filament', 'Admin panel'],
                'views' => 198,
                'title' => [
                    'tr' => 'Filament ne zaman yeterli, ne zaman değil?',
                    'en' => "When is Filament enough, and when isn't it?",
                ],
                'excerpt' => [
                    'tr' => 'Teklif Masası projesinde panelin %80\'i Filament\'in hazır parçalarıyla çıktı. Kalan %20 için ne yaptığımı ve sıfırdan panel yazmayı ne zaman tercih ettiğimi anlatıyorum.',
                    'en' => "On Teklif Masası, 80% of the panel came straight from Filament's building blocks. Here's what I did for the remaining 20%, and when I'd rather build a panel from scratch.",
                ],
                'body' => [
                    'tr' => <<<'MD'
Müşteriler admin panelden genelde aynı şeyleri ister: listeleme, filtreleme, form, yetki ve dışa aktarma. Filament bunların hepsini birkaç satırla veriyor. Teklif Masası'nda 12 kişilik satış ekibi için paneli **10 haftada** teslim edebilmemin en büyük nedeni buydu.

## Filament'in parladığı yerler

- **Standart kayıt ekranları:** müşteri, ürün, fiyat listesi. Tablo, arama, filtre ve form birkaç dakikada hazır.
- **Yetkiler:** Laravel policy'leriyle doğrudan çalışıyor. "Satış temsilcisi yalnızca kendi tekliflerini görsün" kuralı tek bir sorgu kapsamı.
- **Tutarlı arayüz:** Ekip yeni bir ekran açtığında öğrenecek bir şey yok, hepsi aynı şekilde çalışıyor.

## Özel bileşen gerektiren %20

Teklif kalemlerini düzenleme ekranı, ekibin günde onlarca kez kullandığı yerdi. Hazır tekrarlayıcı alan işi görüyordu ama hızlı değildi. Burada Filament sayfasının içine kendi Livewire bileşenimi koydum: klavyeyle satır ekleme, anlık toplam ve fiyat listesinden otomatik doldurma.

```php
// Filament sayfasının içinde özel bileşen
protected string $view = 'filament.pages.quote-editor';
```

Panelin geri kalanı Filament olarak kaldı; yalnızca gerçekten farklı olması gereken ekran özel yazıldı.

## Sıfırdan yazmayı ne zaman seçerim?

- Panel aslında **müşterinin kullandığı bir ürünse** ve markaya özel, tamamen farklı bir deneyim gerekiyorsa.
- Ekranların çoğu standart CRUD değil de sürükle-bırak takvim, harita ya da gerçek zamanlı akış gibi özel arayüzlerden oluşuyorsa.
- Ekip zaten Inertia + Vue ile çalışıyor ve panelin aynı arayüz diliyle büyümesi gerekiyorsa.

## Kısacası

İç ekiplerin kullandığı panellerde Filament ile başlıyorum ve yalnızca gerektiğinde özelleştiriyorum. Bu yaklaşım, bütçenin büyük kısmını gerçekten iş değeri yaratan ekranlara ayırmamı sağlıyor.
MD,
                    'en' => <<<'MD'
Clients usually ask the same things of an admin panel: listing, filtering, forms, permissions and exports. Filament gives you all of them in a few lines. It's the main reason I could deliver the panel for Teklif Masası's 12-person sales team in **10 weeks**.

## Where Filament shines

- **Standard record screens:** customers, products, price lists. Table, search, filters and form are ready in minutes.
- **Permissions:** it works directly with Laravel policies. "Sales reps only see their own quotes" is a single query scope.
- **A consistent interface:** when the team opens a new screen there's nothing to learn; everything works the same way.

## The 20% that needed custom components

The quote line editor was where the team spent dozens of moments a day. The built-in repeater did the job, but it wasn't fast. Here I placed my own Livewire component inside a Filament page: adding rows from the keyboard, live totals and autofill from the price list.

```php
// A custom component inside a Filament page
protected string $view = 'filament.pages.quote-editor';
```

The rest of the panel stayed plain Filament; only the screen that truly needed to be different was custom-built.

## When do I build from scratch?

- When the panel is really **a product your customers use** and needs a fully branded, distinct experience.
- When most screens aren't standard CRUD but custom interfaces like drag-and-drop calendars, maps or real-time feeds.
- When the team already works with Inertia + Vue and the panel needs to grow in the same interface language.

## In short

For panels used by internal teams I start with Filament and customise only when needed. That lets me spend most of the budget on the screens that create real business value.
MD,
                ],
            ],
        ];
    }
}
