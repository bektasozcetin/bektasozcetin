<?php

/*
| Sitenin kişisel bilgileri. Değerleri .env dosyasından değiştir.
| Köşeli parantezli değerler yer tutucudur: yayından önce doldur.
*/

return [
    'name' => 'Bektaş Özçetin',
    'email' => env('SITE_EMAIL', '[e-posta adresin]'),
    'city' => env('SITE_CITY', '[Şehir]'),
    'reply_time' => [
        'tr' => env('SITE_REPLY_TIME_TR', '24 saat'),
        'en' => env('SITE_REPLY_TIME_EN', '24 hours'),
    ],
    'available' => (bool) env('SITE_AVAILABLE', true),
    'cv_url' => env('SITE_CV_URL'),

    'social' => [
        'GitHub' => env('SITE_GITHUB_URL'),
        'LinkedIn' => env('SITE_LINKEDIN_URL'),
        'Instagram' => env('SITE_INSTAGRAM_URL'),
    ],

    // İletişim formundan gelen talepler bu adrese bildirilir.
    'notify_email' => env('SITE_NOTIFY_EMAIL') ?: env('MAIL_FROM_ADDRESS'),

    // Yayında /admin paneline yalnızca bu e-posta girebilir.
    'admin_email' => env('ADMIN_EMAIL'),

    // Yayından önce false yap: projelerdeki "örnek veri" etiketi kaybolur.
    'show_sample_badge' => (bool) env('SITE_SHOW_SAMPLE_BADGE', true),
];
