<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Basit görüntülenme sayacı: botları saymaz, aynı oturumda aynı içeriği bir kez sayar.
 * Kişisel veri tutmaz; yalnızca kayıttaki "views" sayısını artırır.
 */
trait CountsViews
{
    protected function countView(Request $request, Model $model): void
    {
        $agent = (string) $request->userAgent();
        if ($agent === '' || preg_match('/bot|crawl|spider|slurp|preview|facebookexternalhit|whatsapp|headless|lighthouse/i', $agent)) {
            return;
        }

        $key = 'viewed.'.$model->getTable().'.'.$model->getKey();
        if ($request->session()->has($key)) {
            return;
        }
        $request->session()->put($key, true);

        $model::withoutTimestamps(fn () => $model->increment('views'));
    }
}
