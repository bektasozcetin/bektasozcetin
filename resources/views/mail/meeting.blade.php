Yeni görüşme talebi (onay bekliyor)

Tarih: {{ $meeting->starts_at->setTimezone('Europe/Istanbul')->locale('tr')->translatedFormat('j F Y l, H:i') }} ({{ $meeting->duration }} dk, Türkiye saati)
Ad: {{ $meeting->name }}
E-posta: {{ $meeting->email }}
Konu: {{ __('site.contact.types')[$meeting->topic] ?? '-' }}
Dil: {{ $meeting->locale }}

{{ $meeting->note ?: '(not yok)' }}

Onaylamak için: {{ url('/admin/meetings') }}
