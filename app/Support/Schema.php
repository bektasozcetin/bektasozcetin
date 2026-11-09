<?php

namespace App\Support;

use App\Models\Post;

/**
 * Schema.org JSON-LD üretir.
 *
 * Bu kod bilerek Blade şablonunda değil: Laravel 11 ile gelen `@context`
 * Blade direktifi, şablondaki '@context' anahtarını PHP koduna çeviriyor ve
 * çıktıyı bozuyordu. Burada anahtar düz metin olarak güvende.
 */
class Schema
{
    private const CONTEXT = 'https://schema.org';

    /** Ana kimlik: her sayfada basılan Person şeması. */
    public static function person(): string
    {
        return self::encode([
            '@context' => self::CONTEXT,
            '@type' => 'Person',
            'name' => config('site.name'),
            'jobTitle' => 'Full Stack Laravel Developer',
            'url' => url('/'),
            'image' => asset('images/portrait.jpg'),
            'address' => array_filter([
                '@type' => 'PostalAddress',
                'addressLocality' => config('site.city'),
                'addressCountry' => 'TR',
            ]),
            'sameAs' => array_values(array_filter(config('site.social'))),
        ]);
    }

    /** Not (blog yazısı) sayfası. */
    public static function blogPosting(Post $post, string $title, string $url, string $image): string
    {
        return self::encode([
            '@context' => self::CONTEXT,
            '@type' => 'BlogPosting',
            'headline' => $title,
            'description' => $post->t('excerpt'),
            'datePublished' => $post->published_at->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
            'inLanguage' => app()->getLocale(),
            'image' => $image,
            'url' => $url,
            'keywords' => implode(', ', $post->tags ?? []),
            'author' => ['@type' => 'Person', 'name' => config('site.name'), 'url' => url('/')],
            'publisher' => ['@type' => 'Person', 'name' => config('site.name'), 'url' => url('/')],
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $url],
        ]);
    }

    /**
     * SSS sayfası: Google'ın arama sonucunda açılır soru gösterebilmesi için.
     *
     * @param  iterable<int, object>  $faqs
     */
    public static function faqPage(iterable $faqs): ?string
    {
        $sorular = [];

        foreach ($faqs as $faq) {
            $sorular[] = [
                '@type' => 'Question',
                'name' => $faq->t('question'),
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq->t('answer')],
            ];
        }

        return $sorular === [] ? null : self::encode([
            '@context' => self::CONTEXT,
            '@type' => 'FAQPage',
            'mainEntity' => $sorular,
        ]);
    }

    /** @param  array<string, mixed>  $data */
    private static function encode(array $data): string
    {
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }
}
