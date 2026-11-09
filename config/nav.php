<?php

/*
| Sitenin alt sayfaları: tek doğruluk kaynağı.
|
| Rotalar, başlık menüsü, mobil menü, komut paleti ve site haritası hep bu
| listeyi okur; yeni bir sayfa eklemek için tek yapılacak buraya satır eklemek.
|
| slug      → adres parçası (dile göre)
| key       → lang/<dil>/site.php içindeki nav.<key> etiketi
| sections  → sayfanın gösterdiği partials/sections/<ad>.blade.php dosyaları
| needs     → denetleyicinin o sayfa için çekeceği veriler
*/

return [

    'pages' => [

        'services' => [
            'slug' => ['tr' => 'hizmetler', 'en' => 'services'],
            'key' => 'services',
            'sections' => ['hizmetler'],
            'needs' => ['services'],
        ],

        'skills' => [
            'slug' => ['tr' => 'yetkinlikler', 'en' => 'skills'],
            'key' => 'skills',
            'sections' => ['yetkinlikler', 'kod'],
            'needs' => ['skills'],
        ],

        'about' => [
            'slug' => ['tr' => 'hakkimda', 'en' => 'about'],
            'key' => 'about',
            'sections' => ['hakkimda', 'ilkeler'],
            'needs' => [],
            // Bu bolumun kendi basligi yok; sayfa basligini pages/show yazar.
            'own_heading' => true,
        ],

        'experience' => [
            'slug' => ['tr' => 'deneyim', 'en' => 'experience'],
            'key' => 'experience',
            'sections' => ['deneyim'],
            'needs' => ['experiences', 'educations'],
        ],

        'pricing' => [
            'slug' => ['tr' => 'fiyatlar', 'en' => 'pricing'],
            'key' => 'models',
            'sections' => ['calisma'],
            'needs' => ['plans'],
        ],

        'testimonials' => [
            'slug' => ['tr' => 'referanslar', 'en' => 'testimonials'],
            'key' => 'refs',
            'sections' => ['referanslar'],
            'needs' => ['testimonials'],
        ],

        'faq' => [
            'slug' => ['tr' => 'sss', 'en' => 'faq'],
            'key' => 'faq',
            'sections' => ['sss'],
            'needs' => ['faqs'],
        ],

    ],

];
