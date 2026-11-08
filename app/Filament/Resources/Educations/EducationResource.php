<?php

namespace App\Filament\Resources\Educations;

use App\Filament\Resources\Educations\Pages\CreateEducation;
use App\Filament\Resources\Educations\Pages\EditEducation;
use App\Filament\Resources\Educations\Pages\ListEducations;
use App\Filament\Resources\Educations\Schemas\EducationForm;
use App\Filament\Resources\Educations\Tables\EducationsTable;
use App\Models\Education;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EducationResource extends Resource
{
    protected static ?string $model = Education::class;

    protected static ?string $slug = 'educations';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static string|UnitEnum|null $navigationGroup = 'İçerik';

    protected static ?int $navigationSort = 5;

    protected static ?string $modelLabel = 'Eğitim / sertifika';

    protected static ?string $pluralModelLabel = 'Eğitim & sertifikalar';

    public static function form(Schema $schema): Schema
    {
        return EducationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EducationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEducations::route('/'),
            'create' => CreateEducation::route('/create'),
            'edit' => EditEducation::route('/{record}/edit'),
        ];
    }
}
