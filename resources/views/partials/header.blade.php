@php
    $home = localized_route('home');
    // Başlık menüsü dar: yalnızca üç alt sayfa. Kalanlar mobil menüde,
    // komut paletinde ve ana sayfadaki "keşfet" bölümünde.
    $links = ['services', 'skills', 'about'];
@endphp
<header class="glass fade-up sticky top-4 z-[5] flex items-center justify-between gap-2.5 rounded-full py-2 pr-2 pl-2.5">
    <a href="{{ $home }}#top" class="flex min-h-11 min-w-0 items-center gap-3 pr-2 no-underline">
        <span class="inline-flex h-10 w-10 rounded-full bg-[conic-gradient(from_200deg,var(--c5),var(--c0),var(--v),var(--c0),var(--c5))] p-0.5">
            <img src="{{ asset('images/portrait.jpg') }}" alt="" width="36" height="36" class="block h-full w-full rounded-full object-cover object-[50%_28%]">
        </span>
        {{-- Alan adı 17 karakter; dar telefonlarda üç noktayla kesilmesin diye
             kademeli küçülüyor. truncate yalnızca son çare. --}}
        {{-- Telefonda yalnızca avatar; tablet ve üstünde ad yazılır. --}}
        <span class="hidden truncate font-display text-base font-semibold tracking-[-0.02em] md:inline">Bektaş <span class="serif volt text-[19px]">Özçetin</span></span>
    </a>

    <nav class="nav-desk items-center justify-end gap-0.5" aria-label="{{ __('site.nav.menu_label') }}">
        <a class="navlink" href="{{ $home }}#projeler">{{ __('site.nav.work') }}</a>
        @foreach ($links as $name)
            <a class="navlink" href="{{ localized_route($name) }}" @if (request()->routeIs($name, 'en.'.$name)) aria-current="page" @endif>{{ __('site.nav.'.config('nav.pages.'.$name.'.key')) }}</a>
        @endforeach
        <a class="navlink" href="{{ localized_route('posts.index') }}" @if (request()->routeIs('posts.*', 'en.posts.*')) aria-current="page" @endif>{{ __('site.nav.notes') }}</a>
        @include('partials.lang-switch', ['class' => 'ml-1 border border-line p-[3px]'])
        <button type="button" class="navlink ml-1 w-11 cursor-pointer justify-center border border-line bg-fill-soft p-0" @click="toggleTheme" :aria-label="themeLabel" :title="themeLabel">
            <x-ico name="sun" x-show="theme === 'dark'" />
            <x-ico name="moon" x-show="theme === 'light'" x-cloak />
        </button>
        {{-- "Hızlı geçiş" düğmesi menüyü kalabalıklaştırıyordu; palet Ctrl/⌘+K
             ile ve mobildeki arama simgesiyle açılmaya devam ediyor. --}}
        <a class="btn btn-primary btn-sm ml-1.5" href="{{ $home }}#iletisim" @click="book($event)">{{ __('site.cta') }}</a>
    </nav>

    <div class="nav-mob shrink-0 items-center gap-1.5">
        <button type="button" class="navlink w-11 cursor-pointer justify-center border border-line bg-fill-soft p-0" @click="toggleTheme" :aria-label="themeLabel">
            <x-ico name="sun" x-show="theme === 'dark'" />
            <x-ico name="moon" x-show="theme === 'light'" x-cloak />
        </button>
        <button type="button" class="navlink w-11 cursor-pointer justify-center border border-line bg-fill-soft p-0" @click="openPalette" aria-label="{{ __('site.nav.quick') }}" aria-haspopup="dialog">
            <x-ico name="search" />
        </button>
        <button type="button" class="btn btn-light btn-sm" @click="menu = true; focusDialog()" aria-haspopup="dialog" :aria-expanded="menu.toString()">
            <x-ico name="menu" :stroke="2.2" />{{ __('site.nav.menu') }}
        </button>
    </div>
    <span aria-hidden="true" class="prog"></span>
</header>
