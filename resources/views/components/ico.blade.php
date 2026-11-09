@props(['name', 'size' => 16, 'stroke' => 2])
@php
    $paths = [
        'arrow' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'arrow-up' => '<path d="M12 19V5M6 11l6-6 6 6"/>',
        'whatsapp' => '<path d="M20.5 11.6a8.4 8.4 0 0 1-12.3 7.5L3.5 20.5l1.4-4.6a8.4 8.4 0 1 1 15.6-4.3z"/><path d="M9 9.2c.2 2.4 2.4 4.6 4.8 4.8.5 0 1.1-.3 1.4-.7.2-.3.1-.6-.1-.8l-1-.8c-.2-.2-.5-.2-.8 0l-.5.4c-.9-.4-1.6-1.1-2-2l.4-.5c.2-.3.2-.6 0-.8l-.8-1c-.2-.2-.5-.3-.8-.1-.4.3-.7.9-.6 1.5z"/>',
        'calendar' => '<rect x="3.5" y="5" width="17" height="15.5" rx="3"/><path d="M3.5 10h17M8 3v4M16 3v4"/>',
        'link' => '<path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1"/>',
        'check' => '<path d="M5 12l5 5 9-10"/>',
        'close' => '<path d="M6 6l12 12M18 6L6 18"/>',
        'search' => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/>',
        'menu' => '<path d="M4 8h16M4 16h16"/>',
        'sun' => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
        'moon' => '<path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/>',
        'plus' => '<path d="M12 5v14M5 12h14"/>',
        'copy' => '<rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15V5a2 2 0 0 1 2-2h10"/>',
        'send' => '<path d="M22 2L11 13M22 2l-7 20-4-9-9-4z"/>',
        'file' => '<path d="M4 4h12l4 4v12H4z"/><path d="M8 12h8M8 16h5"/>',
        'layers' => '<path d="M4 7l8-4 8 4-8 4z"/><path d="M4 12l8 4 8-4"/><path d="M4 17l8 4 8-4"/>',
        'braces' => '<path d="M8 4H6a2 2 0 0 0-2 2v4l-2 2 2 2v4a2 2 0 0 0 2 2h2M16 4h2a2 2 0 0 1 2 2v4l2 2-2 2v4a2 2 0 0 1-2 2h-2"/>',
        'wave' => '<path d="M3 12c2-4 4-6 7-6 4 0 4 4 7 4 2 0 3-1 4-2M3 18c2-4 4-6 7-6 4 0 4 4 7 4 2 0 3-1 4-2"/>',
        'db' => '<ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v14c0 1.7 3.6 3 8 3s8-1.3 8-3V5"/><path d="M4 12c0 1.7 3.6 3 8 3s8-1.3 8-3"/>',
        'box' => '<path d="M12 3l8 4.5v9L12 21l-8-4.5v-9z"/><path d="M12 12l8-4.5M12 12v9M12 12L4 7.5"/>',
        'app' => '<path d="M4 5h16v12H4zM8 21h8M12 17v4"/>',
        'cart' => '<path d="M3 4h2l2.4 11h11.2L21 7H6.2M9 20a1 1 0 1 0 0-.01M18 20a1 1 0 1 0 0-.01"/>',
        'panel' => '<path d="M3 3h8v10H3zM13 3h8v6h-8zM13 11h8v10h-8zM3 15h8v6H3z"/>',
        'api' => '<path d="M8 4H6a2 2 0 0 0-2 2v4l-2 2 2 2v4a2 2 0 0 0 2 2h2M16 4h2a2 2 0 0 1 2 2v4l2 2-2 2v4a2 2 0 0 1-2 2h-2"/>',
        'up' => '<path d="M12 19V5M5 12l7-7 7 7M5 21h14"/>',
        'gauge' => '<path d="M12 14l4-4M3 14a9 9 0 1 1 18 0M12 14h.01"/>',
    ];
@endphp
<svg {{ $attributes->merge(['aria-hidden' => 'true']) }} width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ $stroke }}" stroke-linecap="round" stroke-linejoin="round">{!! $paths[$name] ?? '' !!}</svg>
