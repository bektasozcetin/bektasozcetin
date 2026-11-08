{{-- Giriş sayfası düzeni: Filament'in temel düzeni (stiller, Livewire, bildirimler) + sitenin görünümü --}}
@push('scripts')
    <script>
        // Giriş sayfası her zaman sitenin koyu temasında görünür
        document.documentElement.classList.add('dark');
    </script>
@endpush

<x-filament-panels::layout.base :livewire="$livewire">
    @once
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,400&family=IBM+Plex+Mono:wght@400;500&display=swap">
        @vite('resources/css/admin-login.css')
    @endonce

    {{ $slot }}
</x-filament-panels::layout.base>
