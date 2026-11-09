<x-layouts.app :title="$metaTitle" :description="$metaDescription">
    @if ($page === 'faq' && ($faqSchema = \App\Support\Schema::faqPage($faqs ?? [])))
        {{-- Google arama sonucunda açılır soru-cevap gösterebilsin. --}}
        <x-slot:head>
            <script type="application/ld+json">{!! $faqSchema !!}</script>
        </x-slot:head>
    @endif

    {{-- Ana sayfaya dönüş: alt sayfada kaybolmasın. --}}
    <nav class="fade-up -mb-4" aria-label="breadcrumb">
        <a class="navlink -ml-3.5 inline-flex items-center gap-2 text-sm" href="{{ localized_route('home') }}">
            <x-ico name="arrow" :size="15" class="rotate-180" />{{ __('site.pages.back') }}
        </a>
    </nav>

    {{-- Bölümün kendi başlığı yoksa (hakkımda) sayfa başlığını burada yazıyoruz;
         diğer sayfalarda ilk bölümün başlığı h1'e yükseltiliyor, iki başlık olmasın. --}}
    @if ($ownHeading)
        <header class="fade-up flex flex-col gap-5">
            <h1 class="h2 m-0">{{ $heading }}</h1>
            <p class="lede m-0 max-w-[58ch]">{{ $lede }}</p>
        </header>
    @endif

    @foreach ($sections as $section)
        @include('partials.sections.'.$section, ['headLevel' => ! $ownHeading && $loop->first ? 'h1' : 'h2'])
    @endforeach

    {{-- Her alt sayfanın sonunda iletişime çıkış --}}
    <aside class="card spot reveal flex flex-wrap items-center justify-between gap-6 rounded-[32px] p-[clamp(22px,4vw,44px)]">
        <div class="flex min-w-0 flex-col gap-2">
            <h2 class="h3">{{ __('site.pages.cta_title') }}</h2>
            <p class="m-0 max-w-[48ch] text-c4">{{ __('site.pages.cta_lede') }}</p>
        </div>
        <a class="btn btn-primary" href="{{ localized_route('home') }}#iletisim" @click="book($event)">
            {{ __('site.cta') }}<x-ico name="arrow" :size="17" />
        </a>
    </aside>
</x-layouts.app>
