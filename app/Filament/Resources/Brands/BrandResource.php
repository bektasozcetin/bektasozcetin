<?php

namespace App\Filament\Resources\Brands;

use App\Filament\Resources\Brands\Pages\CreateBrand;
use App\Filament\Resources\Brands\Pages\EditBrand;
use App\Filament\Resources\Brands\Pages\ListBrands;
use App\Models\Brand;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static string|UnitEnum|null $navigationGroup = 'İçerik';

    protected static ?int $navigationSort = 0;

    protected static ?string $modelLabel = 'Marka';

    protected static ?string $pluralModelLabel = 'Markalar';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Marka')->columns(2)->columnSpanFull()->schema([
                TextInput::make('name')->label('Marka adı')->required(),
                TextInput::make('url')->label('Web sitesi')->url(),
                FileUpload::make('logo')->label('Logo')
                    ->helperText('Tek renkli SVG ya da şeffaf PNG önerilir. Sitede temaya göre otomatik beyaz/siyah gösterilir.')
                    ->disk('public')->directory('brands')
                    ->acceptedFileTypes(['image/svg+xml', 'image/png', 'image/webp'])
                    ->columnSpanFull(),
                TextInput::make('sort')->label('Sıra')->numeric()->default(0),
                Toggle::make('is_published')->label('Yayında')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')->label('Logo')->disk('public')->height(28)->extraImgAttributes(['style' => 'background:#fff;padding:4px;border-radius:6px']),
                TextColumn::make('name')->label('Marka')->searchable()->weight('bold'),
                TextColumn::make('url')->label('Web sitesi')->limit(40),
                ToggleColumn::make('is_published')->label('Yayında'),
            ])
            ->defaultSort('sort')
            ->reorderable('sort')
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBrands::route('/'),
            'create' => CreateBrand::route('/create'),
            'edit' => EditBrand::route('/{record}/edit'),
        ];
    }
}
