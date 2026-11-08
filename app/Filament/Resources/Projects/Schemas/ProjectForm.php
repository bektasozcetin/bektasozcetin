<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Filament\Support\Translatable;
use App\Models\Project;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Genel')->columns(2)->columnSpanFull()->schema([
                TextInput::make('slug')->label('Adres (slug)')->required()->unique(ignoreRecord: true)->alphaDash()
                    ->helperText('Proje sayfasının adresi: /projeler/{slug}'),
                Select::make('category')->label('Kategori')->options(Project::CATEGORIES)->required(),
                TextInput::make('year')->label('Yıl')->required()->numeric()->minValue(2000)->maxValue(2100),
                TextInput::make('sort')->label('Sıra')->numeric()->default(0),
                TagsInput::make('tags')->label('Teknolojiler')->columnSpanFull(),
                FileUpload::make('image')->label('Ekran görüntüsü (16:10, en az 1600 px)')->image()->disk('public')->directory('projects')->columnSpanFull(),
                FileUpload::make('gallery')->label('Galeri: ek ekran görüntüleri (sürükleyerek sırala)')->image()->multiple()->reorderable()->maxFiles(8)->disk('public')->directory('projects/gallery')->panelLayout('grid')->columnSpanFull(),
                TextInput::make('live_url')->label('Canlı demo adresi')->url(),
                TextInput::make('code_url')->label('Kod adresi (GitHub)')->url(),
                Toggle::make('is_published')->label('Yayında')->default(true),
            ]),
            Section::make('Metinler')->description('Türkçe zorunlu; İngilizce boş kalırsa sitede Türkçe gösterilir.')->columnSpanFull()->schema([
                Translatable::tabs([
                    ['type', 'Tür etiketi (ör. E-TİCARET)', 'text', true],
                    ['title', 'Proje adı', 'text', true],
                    ['description', 'Kısa açıklama', 'textarea', true],
                    ['result', 'Ölçülebilir sonuç', 'text', true],
                    ['role', 'Rolüm', 'text', false],
                    ['duration', 'Süre', 'text', false],
                    ['problem', 'Sorun', 'textarea', false],
                    ['approach', 'Yaklaşım', 'textarea', false],
                    ['lesson', 'Bugün olsa neyi farklı yapardım?', 'textarea', false],
                ]),
                Repeater::make('decisions')->label('Teknik kararlar')->schema([
                    Grid::make(2)->schema([
                        TextInput::make('q.tr')->label('Soru (TR)')->required(),
                        TextInput::make('q.en')->label('Question (EN)'),
                        Textarea::make('a.tr')->label('Cevap (TR)')->required()->rows(2),
                        Textarea::make('a.en')->label('Answer (EN)')->rows(2),
                    ]),
                ])->defaultItems(0)->reorderable()->collapsible()->columnSpanFull(),
            ]),
        ]);
    }
}
