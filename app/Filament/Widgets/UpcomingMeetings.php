<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Meetings\MeetingResource;
use App\Models\Meeting;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class UpcomingMeetings extends TableWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 0;

    protected static ?string $heading = 'Yaklaşan görüşmeler';

    public function table(Table $table): Table
    {
        return $table
            ->query(Meeting::active()->where('starts_at', '>=', now())->orderBy('starts_at')->limit(5))
            ->paginated(false)
            ->emptyStateHeading('Takvim boş')
            ->emptyStateDescription('Yeni talepler burada görünür.')
            ->emptyStateIcon(Heroicon::OutlinedCalendarDays)
            ->headerActions([
                Action::make('all')->label('Tümü')->link()->url(MeetingResource::getUrl()),
            ])
            ->columns([
                TextColumn::make('starts_at')->label('Zaman')
                    ->formatStateUsing(fn ($state) => $state->locale('tr')->translatedFormat('j M D, H:i'))
                    ->description(fn ($record) => $record->starts_at->locale('tr')->diffForHumans()),
                TextColumn::make('name')->label('Kişi')->weight('bold')
                    ->description(fn ($record) => __('site.contact.types')[$record->topic] ?? null),
                TextColumn::make('status')->label('Durum')->badge()
                    ->formatStateUsing(fn ($state) => Meeting::STATUSES[$state] ?? $state)
                    ->color(fn ($state) => $state === 'confirmed' ? 'success' : 'warning'),
            ])
            ->recordActions([
                Action::make('confirm')->label('Onayla')->icon(Heroicon::Check)->color('success')->iconButton()
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(fn ($record) => $record->update(['status' => 'confirmed'])),
            ]);
    }
}
