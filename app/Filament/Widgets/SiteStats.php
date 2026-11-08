<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use App\Models\Meeting;
use App\Models\Post;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SiteStats extends StatsOverviewWidget
{
    protected static ?int $sort = -2;

    protected function getStats(): array
    {
        $next = Meeting::active()->where('starts_at', '>=', now())->orderBy('starts_at')->first();

        return [
            Stat::make('Proje görüntülenmesi', number_format(Project::sum('views'), 0, ',', '.'))
                ->description('Vaka penceresi + proje sayfası')->icon('heroicon-o-briefcase'),
            Stat::make('Not okunması', number_format(Post::sum('views'), 0, ',', '.'))
                ->description(Post::published()->count().' yayında not')->icon('heroicon-o-pencil-square'),
            Stat::make('Okunmamış mesaj', ContactMessage::whereNull('read_at')->count())
                ->description('İletişim formu')->icon('heroicon-o-inbox')->color('primary'),
            Stat::make('Yaklaşan görüşme', Meeting::active()->where('starts_at', '>=', now())->count())
                ->description($next ? 'Sıradaki: '.$next->starts_at->locale('tr')->translatedFormat('j M D, H:i') : 'Takvim boş')
                ->icon('heroicon-o-calendar-days'),
        ];
    }
}
