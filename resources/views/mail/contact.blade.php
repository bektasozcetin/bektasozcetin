Yeni görüşme talebi

Ad: {{ $contact->name }}
E-posta: {{ $contact->email }}
Proje türü: {{ $contact->project_type ?? '-' }}
Dil: {{ $contact->locale }}

{{ $contact->message }}
