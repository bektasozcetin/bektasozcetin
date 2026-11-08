<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class PopularContent extends TableWidget
{
    protected static ?int $sort = -1;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'En çok bakılan projeler';

    public function table(Table $table): Table
    {
        return $table
            ->query(Project::query()->orderByDesc('views'))
            ->paginated(false)
            ->columns([
                TextColumn::make('title')->label('Proje')->getStateUsing(fn ($record) => $record->t('title'))->weight('bold'),
                TextColumn::make('category')->label('Kategori')->badge()->formatStateUsing(fn ($state) => Project::CATEGORIES[$state] ?? $state),
                TextColumn::make('views')->label('Görüntülenme')->numeric(),
            ]);
    }
}
