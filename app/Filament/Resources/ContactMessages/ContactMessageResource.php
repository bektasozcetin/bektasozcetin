<?php

namespace App\Filament\Resources\ContactMessages;

use App\Filament\Resources\ContactMessages\Pages\ListContactMessages;
use App\Models\ContactMessage;
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
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInbox;

    protected static string|UnitEnum|null $navigationGroup = 'Gelen kutusu';

    protected static ?string $modelLabel = 'Mesaj';

    protected static ?string $pluralModelLabel = 'Mesajlar';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        $unread = ContactMessage::whereNull('read_at')->count();

        return $unread ? (string) $unread : null;
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
            TextInput::make('project_type')->label('Proje türü')
                ->formatStateUsing(fn ($state) => __('site.contact.types')[$state] ?? $state),
            TextInput::make('created_at')->label('Tarih'),
            Textarea::make('message')->label('Mesaj')->rows(8)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                IconColumn::make('read_at')->label('')->boolean()->getStateUsing(fn ($record) => $record->read_at === null)
                    ->trueIcon(Heroicon::Envelope)->falseIcon(Heroicon::EnvelopeOpen)->trueColor('primary')->falseColor('gray'),
                TextColumn::make('name')->label('Ad')->searchable()->weight(fn ($record) => $record->read_at ? null : 'bold'),
                TextColumn::make('email')->label('E-posta')->searchable()->copyable(),
                TextColumn::make('project_type')->label('Tür')->badge()->formatStateUsing(fn ($state) => __('site.contact.types')[$state] ?? $state),
                TextColumn::make('message')->label('Mesaj')->limit(60),
                TextColumn::make('created_at')->label('Tarih')->since()->sortable(),
            ])
            ->filters([
                TernaryFilter::make('read_at')->label('Durum')->nullable()->trueLabel('Okunanlar')->falseLabel('Okunmayanlar'),
            ])
            ->recordActions([
                ViewAction::make()->after(fn ($record) => $record->read_at ?? $record->update(['read_at' => now()])),
                Action::make('reply')->label('Yanıtla')->icon(Heroicon::ArrowUturnLeft)
                    ->url(fn ($record) => 'mailto:'.$record->email.'?subject='.rawurlencode('Re: Görüşme talebi')),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => ListContactMessages::route('/')];
    }
}
