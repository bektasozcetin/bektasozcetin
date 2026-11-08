<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasTranslations;

    protected $guarded = [];

    protected array $translatable = ['title', 'excerpt', 'body'];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Yayın tarihi gelmiş yazılar, yeniden eskiye. */
    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now())->latest('published_at');
    }

    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published_at->lte(now());
    }

    /** Markdown gövdenin güvenli HTML hali (ham HTML etiketleri silinir). */
    public function html(): string
    {
        return Str::markdown($this->t('body') ?? '', [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    /** Dakika cinsinden okuma süresi (dakikada ~200 kelime). */
    public function readingTime(): int
    {
        $words = preg_split('/\s+/u', trim(strip_tags($this->html())), -1, PREG_SPLIT_NO_EMPTY);

        return max(1, (int) ceil(count($words) / 200));
    }

    public function coverUrl(): ?string
    {
        return $this->cover ? asset('storage/'.$this->cover) : null;
    }

    public function url(?string $locale = null): string
    {
        return localized_route('posts.show', $this, $locale);
    }

    public function dateLabel(): string
    {
        return $this->published_at?->locale(app()->getLocale())->translatedFormat('j F Y') ?? '';
    }
}
