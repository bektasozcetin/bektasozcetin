<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    use HasTranslations;

    protected $guarded = [];

    protected array $translatable = ['description', 'kind'];

    protected $casts = ['is_published' => 'boolean'];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('id');
    }
}
