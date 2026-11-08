<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $guarded = [];

    protected $casts = ['starts_at' => 'datetime'];

    public const STATUSES = ['pending' => 'Onay bekliyor', 'confirmed' => 'Onaylandı', 'cancelled' => 'İptal'];

    /** Takvimde yer kaplayan (iptal edilmemiş) görüşmeler. */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', '!=', 'cancelled');
    }
}
