<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasTranslations;

    protected $guarded = [];

    protected array $translatable = ['period', 'company', 'description'];

    protected $casts = ['tags' => 'array', 'is_current' => 'boolean'];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('id');
    }
}
