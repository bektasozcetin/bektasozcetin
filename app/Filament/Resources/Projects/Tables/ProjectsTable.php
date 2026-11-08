<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Models\Project;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label('')->disk('public')->square(),
                TextColumn::make('title')->label('Proje')->getStateUsing(fn ($record) => $record->t('title'))->weight('bold'),
                TextColumn::make('category')->label('Kategori')->badge()->formatStateUsing(fn ($state) => Project::CATEGORIES[$state] ?? $state),
                TextColumn::make('year')->label('Yıl')->sortable(),
                TextColumn::make('views')->label('Görüntülenme')->numeric()->sortable(),
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
