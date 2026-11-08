<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;

/** Pano: başlık yerine karşılama kartı (Widgets\Welcome) kullanılır. */
class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Pano';

    public function getHeading(): string|Htmlable
    {
        return '';
    }
}
