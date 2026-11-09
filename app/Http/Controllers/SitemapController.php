<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $projects = Project::published()->get();
        $posts = Post::published()->get();
        $urls = [];

        foreach (['tr', 'en'] as $locale) {
            $urls[] = localized_route('home', [], $locale);

            foreach (array_keys(config('nav.pages')) as $page) {
                $urls[] = localized_route($page, [], $locale);
            }

            $urls[] = localized_route('privacy', [], $locale);
            foreach ($projects as $project) {
                $urls[] = localized_route('projects.show', $project, $locale);
            }
            $urls[] = localized_route('posts.index', [], $locale);
            foreach ($posts as $post) {
                $urls[] = localized_route('posts.show', $post, $locale);
            }
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($urls as $url) {
            $xml .= '<url><loc>'.e($url).'</loc></url>';
        }
        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
