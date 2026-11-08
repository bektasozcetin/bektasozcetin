<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Support\CountsViews;
use App\Support\OgImage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    use CountsViews;

    public function index(Request $request): View
    {
        $posts = Post::published()->get();
        $tag = $request->query('etiket');
        $tags = $posts->pluck('tags')->flatten()->filter()->countBy()->sortDesc()->keys();

        return view('posts.index', [
            'posts' => $tag ? $posts->filter(fn ($p) => in_array($tag, $p->tags ?? [], true))->values() : $posts,
            'tags' => $tags,
            'activeTag' => $tag,
            'ogImage' => OgImage::make(__('site.notes.eyebrow_plain'), __('site.notes.og_title'), config('site.name')),
        ]);
    }

    public function show(Request $request, Post $post): View
    {
        abort_unless($post->isPublished(), 404);
        $this->countView($request, $post);

        $published = Post::published()->get(['id', 'slug', 'title', 'excerpt', 'published_at', 'tags', 'body']);
        $index = $published->search(fn ($p) => $p->id === $post->id);

        return view('posts.show', [
            'post' => $post,
            'newer' => $index > 0 ? $published[$index - 1] : null,
            'older' => $published[$index + 1] ?? null,
            'ogImage' => OgImage::make(
                __('site.notes.kicker', ['min' => $post->readingTime()]),
                $post->t('title'),
                config('site.name'),
                $post->cover ? storage_path('app/public/'.$post->cover) : null,
            ),
        ]);
    }
}
