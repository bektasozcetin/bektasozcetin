<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasTranslations;

    protected $guarded = [];

    protected array $translatable = ['type', 'title', 'description', 'result', 'role', 'duration', 'problem', 'approach', 'lesson'];

    protected $casts = [
        'decisions' => 'array',
        'tags' => 'array',
        'gallery' => 'array',
        'is_published' => 'boolean',
    ];

    public const CATEGORIES = ['web' => 'Web uygulama', 'panel' => 'Admin panel', 'api' => 'API'];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort')->orderByDesc('year');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Kapak + galeri görsellerinin adresleri (kapak her zaman ilk sırada). */
    public function galleryUrls(): array
    {
        return collect([$this->image, ...($this->gallery ?? [])])
            ->filter()->unique()->map(fn ($path) => asset('storage/'.$path))->values()->all();
    }

    /** Yayındaki sıraya göre bir sonraki proje (sondaysa ilk proje). */
    public function nextProject(): ?self
    {
        $all = static::published()->get(['id', 'slug', 'title', 'type', 'image', 'year']);
        if ($all->count() < 2) {
            return null;
        }
        $index = $all->search(fn ($p) => $p->id === $this->id);

        return $all[($index === false ? -1 : $index) + 1] ?? $all->first();
    }

    /** Vaka çalışmasının dile göre çözülmüş hali (modal ve detay sayfası için). */
    public function toCaseStudy(): array
    {
        return [
            'slug' => $this->slug,
            'category' => $this->category,
            'type' => $this->t('type'),
            'title' => $this->t('title'),
            'year' => $this->year,
            'description' => $this->t('description'),
            'result' => $this->t('result'),
            'role' => $this->t('role'),
            'duration' => $this->t('duration'),
            'problem' => $this->t('problem'),
            'approach' => $this->t('approach'),
            'lesson' => $this->t('lesson'),
            'decisions' => collect($this->decisions ?? [])->map(fn ($d) => [
                'q' => static::pick($d['q'] ?? null),
                'a' => static::pick($d['a'] ?? null),
            ])->all(),
            'tags' => $this->tags ?? [],
            'stack' => implode(' · ', $this->tags ?? []),
            'image' => $this->image ? asset('storage/'.$this->image) : null,
            'gallery' => $this->galleryUrls(),
            'live_url' => $this->live_url,
            'code_url' => $this->code_url,
            'url' => localized_route('projects.show', $this),
        ];
    }
}
