<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Faq;
use App\Models\Service;
use App\Models\Skill;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Ana sayfadan ayrılan alt sayfalar: hizmetler, yetkinlikler, hakkımda,
 * deneyim, referanslar ve SSS.
 *
 * Hangi sayfanın hangi bölümleri ve verileri kullandığı config/nav.php
 * dosyasında tanımlı; rota adı `page` varsayılanı olarak geliyor.
 */
class PageController extends Controller
{
    public function show(Request $request): View
    {
        // Rota adı sayfa adıdır: "services" ya da İngilizcede "en.services".
        $page = preg_replace('/^en\./', '', (string) $request->route()?->getName());
        $config = config("nav.pages.{$page}");

        if (! $config) {
            throw new NotFoundHttpException;
        }

        return view('pages.show', [
            'page' => $page,
            'sections' => $config['sections'],
            'heading' => __('site.nav.'.$config['key']),
            'lede' => __('site.pages.'.$page.'.lede'),
            'metaTitle' => __('site.pages.'.$page.'.title'),
            'metaDescription' => __('site.pages.'.$page.'.description'),
            'ownHeading' => (bool) ($config['own_heading'] ?? false),
            ...$this->data($config['needs'] ?? []),
        ]);
    }

    /**
     * Yalnızca sayfanın ihtiyaç duyduğu veriyi çeker; her sayfa tüm siteyi
     * sorgulamasın diye bölüm bazlı.
     *
     * @param  array<int, string>  $needs
     * @return array<string, mixed>
     */
    private function data(array $needs): array
    {
        $kaynaklar = [
            'services' => fn () => Service::where('is_published', true)->ordered()->get(),
            'skills' => fn () => Skill::published()->get()->groupBy('group'),
            'experiences' => fn () => Experience::ordered()->get(),
            'educations' => fn () => Education::ordered()->get(),
            'testimonials' => fn () => Testimonial::where('is_published', true)->ordered()->get(),
            'faqs' => fn () => Faq::where('is_published', true)->ordered()->get(),
        ];

        $veri = [];

        foreach ($needs as $need) {
            $veri[$need] = $kaynaklar[$need]();
        }

        return $veri;
    }
}
