<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Response;

/** Notlar için RSS beslemesi (Türkçe). */
class FeedController extends Controller
{
    public function __invoke(): Response
    {
        app()->setLocale('tr');
        $posts = Post::published()->take(20)->get();

        $items = $posts->map(fn (Post $post) => '<item>'
            .'<title>'.e($post->t('title')).'</title>'
            .'<link>'.e($post->url()).'</link>'
            .'<guid isPermaLink="true">'.e($post->url()).'</guid>'
            .'<pubDate>'.$post->published_at->toRssString().'</pubDate>'
            .'<description>'.e($post->t('excerpt')).'</description>'
            .'</item>')->implode('');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<rss version="2.0"><channel>'
            .'<title>bektasozcetin.com · Notlar</title>'
            .'<link>'.e(localized_route('posts.index')).'</link>'
            .'<description>'.e(__('site.notes.index_lede')).'</description>'
            .'<language>tr</language>'
            .$items
            .'</channel></rss>';

        return response($xml, 200, ['Content-Type' => 'application/rss+xml; charset=UTF-8']);
    }
}
