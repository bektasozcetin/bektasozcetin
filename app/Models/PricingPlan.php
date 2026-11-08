<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    use HasTranslations;

    protected $guarded = [];

    protected array $translatable = ['title', 'badge', 'description', 'price_label', 'price', 'cta'];

    protected $casts = ['items' => 'array', 'is_featured' => 'boolean'];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('id');
    }
}
