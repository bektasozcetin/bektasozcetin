<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $guarded = [];

    protected $casts = ['is_core' => 'boolean', 'is_published' => 'boolean'];

    public const GROUPS = ['backend' => 'Backend', 'frontend' => 'Frontend', 'data' => 'Veri', 'devops' => 'DevOps', 'tools' => 'Araçlar'];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort')->orderByDesc('level');
    }
}
