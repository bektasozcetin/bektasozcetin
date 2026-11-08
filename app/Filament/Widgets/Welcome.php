<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use App\Models\Meeting;
use Filament\Widgets\Widget;

/** Panonun üstündeki karşılama kartı: selam, günün özeti ve hızlı eylemler. */
class Welcome extends Widget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = -3;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.welcome';

    protected function getViewData(): array
    {
        $now = now('Europe/Istanbul');
        $hour = (int) $now->format('G');

        return [
            'greeting' => match (true) {
                $hour >= 5 && $hour < 12 => 'Günaydın',
                $hour >= 12 && $hour < 18 => 'İyi günler',
                $hour >= 18 && $hour < 23 => 'İyi akşamlar',
                default => 'İyi geceler',
            },
            'name' => str(auth()->user()?->name ?? '')->before(' ')->toString(),
            'date' => $now->locale('tr')->translatedFormat('j F Y, l'),
            'pending' => Meeting::where('status', 'pending')->where('starts_at', '>=', now())->count(),
            'today' => Meeting::active()->whereBetween('starts_at', [now()->startOfDay(), now()->endOfDay()])->count(),
            'unread' => ContactMessage::whereNull('read_at')->count(),
        ];
    }
}
