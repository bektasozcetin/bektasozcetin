<?php

namespace App\Filament\Resources\Repositories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class RepositoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Repo')->searchable()->weight('bold')
                    ->description(fn ($record) => str($record->t('description'))->limit(70)->toString())
                    ->url(fn ($record) => $record->url, shouldOpenInNewTab: true),
                TextColumn::make('language')->label('Dil')->badge()->color('gray'),
                TextColumn::make('stars')->label('Yıldız')->numeric()->sortable()->icon(Heroicon::Star)->placeholder('—'),
                TextColumn::make('forks')->label('Fork')->numeric()->placeholder('—')->toggleable(),
                TextColumn::make('pushed_at')->label('Son güncelleme')->since()->sortable()->placeholder('—')
                    ->tooltip(fn ($record) => $record->pushed_at?->format('d.m.Y H:i')),
                TextColumn::make('github_id')->label('Kaynak')->badge()
                    ->getStateUsing(fn ($record) => $record->github_id ? 'GitHub' : 'Elle')
                    ->color(fn ($state) => $state === 'GitHub' ? 'primary' : 'gray'),
                ToggleColumn::make('is_published')->label('Yayında'),
            ])
            ->defaultSort('sort')
            ->reorderable('sort')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
