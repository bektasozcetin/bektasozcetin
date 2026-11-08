<?php

namespace App\Filament\Resources\Experiences\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExperiencesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('role')->label('Pozisyon')->weight('bold'),
                TextColumn::make('company')->label('Şirket')->getStateUsing(fn ($record) => $record->t('company')),
                TextColumn::make('period')->label('Dönem')->getStateUsing(fn ($record) => $record->t('period')),
                IconColumn::make('is_current')->label('Güncel')->boolean(),
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
