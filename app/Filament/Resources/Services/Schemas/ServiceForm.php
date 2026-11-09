<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Filament\Support\Translatable;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Genel')->columns(2)->columnSpanFull()->schema([
                Select::make('icon')->label('İkon')->required()->options([
                    'app' => 'Web uygulaması', 'cart' => 'E-ticaret', 'panel' => 'Panel', 'api' => 'API', 'up' => 'Yükseltme', 'gauge' => 'Performans',
                    'calendar' => 'Randevu / takvim', 'layers' => 'Kurumsal / katmanlar', 'db' => 'Veritabanı', 'braces' => 'Kod', 'box' => 'DevOps', 'file' => 'Belge',
                ]),
                TextInput::make('sort')->label('Sıra')->numeric()->default(0),
                FileUpload::make('image')->label('Görsel (16:10, en az 1200 px) — boş bırakılırsa yalnızca ikon görünür')->image()->disk('public')->directory('services')->columnSpanFull(),
                TagsInput::make('tags')->label('Etiketler')->columnSpanFull(),
                Toggle::make('is_published')->label('Yayında')->default(true),
            ]),
            Section::make('Metinler')->description('Türkçe zorunlu; İngilizce boş kalırsa sitede Türkçe gösterilir.')->columnSpanFull()->schema([
                Translatable::tabs([
                    ['title', 'Başlık', 'text', true],
                    ['description', 'Açıklama', 'textarea', true],
                ]),
            ]),
        ]);
    }
}
