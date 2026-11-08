<?php

namespace App\Filament\Resources\Skills;

use App\Filament\Resources\Skills\Pages\ManageSkills;
use App\Models\Skill;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use UnitEnum;

class SkillResource extends Resource
{
    protected static ?string $model = Skill::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|UnitEnum|null $navigationGroup = 'İçerik';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Yetenek';

    protected static ?string $pluralModelLabel = 'Yetenekler';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            TextInput::make('name')->label('Yetenek')->placeholder('ör. Laravel')->required()->maxLength(60),
            Select::make('group')->label('Grup')->options(Skill::GROUPS)->required()->native(false),
            Slider::make('level')->label('Seviye (%)')->range(minValue: 10, maxValue: 100)->step(5)->fillTrack()->tooltips()->default(80)->columnSpanFull(),
            TextInput::make('years')->label('Deneyim (yıl)')->numeric()->minValue(0)->maxValue(40),
            TextInput::make('sort')->label('Sıra')->numeric()->default(0),
            Toggle::make('is_core')->label('Ana uzmanlık')->helperText('Sitede vurgulanır.'),
            Toggle::make('is_published')->label('Yayında')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->reorderable('sort')
            ->defaultGroup(Group::make('group')->label('Grup')->getTitleFromRecordUsing(fn ($record) => Skill::GROUPS[$record->group] ?? $record->group))
            ->columns([
                TextColumn::make('name')->label('Yetenek')->weight('bold')->searchable(),
                ViewColumn::make('level')->label('Seviye')->view('filament.columns.level'),
                TextColumn::make('years')->label('Deneyim')->suffix(' yıl')->placeholder('—'),
                IconColumn::make('is_core')->label('Ana')->boolean()->trueIcon(Heroicon::Star)->falseIcon(Heroicon::Minus)->trueColor('primary'),
                ToggleColumn::make('is_published')->label('Yayında'),
            ])
            ->filters([
                SelectFilter::make('group')->label('Grup')->options(Skill::GROUPS),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageSkills::route('/')];
    }
}
