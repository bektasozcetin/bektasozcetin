<?php

namespace App\Filament\Resources\Meetings;

use App\Filament\Resources\Meetings\Pages\ListMeetings;
use App\Models\Meeting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class MeetingResource extends Resource
{
    protected static ?string $model = Meeting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|UnitEnum|null $navigationGroup = 'Gelen kutusu';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Görüşme';

    protected static ?string $pluralModelLabel = 'Görüşme takvimi';

    public static function getNavigationBadge(): ?string
    {
        $pending = Meeting::where('status', 'pending')->where('starts_at', '>=', now())->count();

        return $pending ? (string) $pending : null;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            TextInput::make('name')->label('Ad'),
            TextInput::make('email')->label('E-posta'),
            TextInput::make('starts_at')->label('Tarih ve saat')->formatStateUsing(fn ($record) => $record?->starts_at?->locale('tr')->translatedFormat('j F Y l, H:i')),
            TextInput::make('topic')->label('Konu')->formatStateUsing(fn ($state) => __('site.contact.types')[$state] ?? $state),
            Textarea::make('note')->label('Not')->rows(5)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('starts_at')
            ->columns([
                TextColumn::make('starts_at')->label('Tarih')->sortable()
                    ->formatStateUsing(fn ($state) => $state->locale('tr')->translatedFormat('j M D, H:i'))
                    ->description(fn ($record) => $record->starts_at->locale('tr')->diffForHumans()),
                TextColumn::make('name')->label('Ad')->searchable()->weight('bold'),
                TextColumn::make('email')->label('E-posta')->searchable()->copyable(),
                TextColumn::make('topic')->label('Konu')->badge()->color('gray')->formatStateUsing(fn ($state) => __('site.contact.types')[$state] ?? $state),
                TextColumn::make('status')->label('Durum')->badge()
                    ->formatStateUsing(fn ($state) => Meeting::STATUSES[$state] ?? $state)
                    ->color(fn ($state) => match ($state) { 'confirmed' => 'success', 'cancelled' => 'danger', default => 'warning' }),
            ])
            ->filters([
                TernaryFilter::make('upcoming')->label('Zaman')->trueLabel('Yaklaşanlar')->falseLabel('Geçmiş')->default(true)
                    ->queries(
                        true: fn (Builder $q) => $q->where('starts_at', '>=', now()->startOfDay()),
                        false: fn (Builder $q) => $q->where('starts_at', '<', now()->startOfDay()),
                        blank: fn (Builder $q) => $q,
                    ),
                SelectFilter::make('status')->label('Durum')->options(Meeting::STATUSES),
            ])
            ->recordActions([
                Action::make('confirm')->label('Onayla')->icon(Heroicon::Check)->color('success')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(fn ($record) => $record->update(['status' => 'confirmed'])),
                Action::make('cancel')->label('İptal et')->icon(Heroicon::XMark)->color('danger')->requiresConfirmation()
                    ->visible(fn ($record) => $record->status !== 'cancelled')
                    ->action(fn ($record) => $record->update(['status' => 'cancelled'])),
                Action::make('mail')->label('E-posta')->icon(Heroicon::Envelope)->color('gray')
                    ->url(fn ($record) => 'mailto:'.$record->email.'?subject='.rawurlencode('Görüşmemiz: '.$record->starts_at->locale('tr')->translatedFormat('j F, H:i'))),
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => ListMeetings::route('/')];
    }
}
