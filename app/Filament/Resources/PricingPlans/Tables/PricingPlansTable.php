<?php

namespace App\Filament\Resources\PricingPlans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PricingPlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Model')->getStateUsing(fn ($record) => $record->t('title'))->weight('bold'),
                TextColumn::make('price')->label('Fiyat')->getStateUsing(fn ($record) => $record->t('price')),
                IconColumn::make('is_featured')->label('Öne çıkan')->boolean(),
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
