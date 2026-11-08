<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasTranslations;

    protected $guarded = [];

    protected $table = 'educations';

    protected array $translatable = ['kind', 'title', 'subtitle'];

    protected $casts = [];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('id');
    }
}
