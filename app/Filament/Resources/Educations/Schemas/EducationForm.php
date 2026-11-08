<?php

namespace App\Filament\Resources\Educations\Schemas;

use App\Filament\Support\Translatable;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EducationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Genel')->columns(2)->columnSpanFull()->schema([
                TextInput::make('sort')->label('Sıra')->numeric()->default(0),
            ]),
            Section::make('Metinler')->description('Türkçe zorunlu; İngilizce boş kalırsa sitede Türkçe gösterilir.')->columnSpanFull()->schema([
                Translatable::tabs([
                    ['kind', 'Tür (Eğitim, Sertifika…)', 'text', true],
                    ['title', 'Başlık', 'text', true],
                    ['subtitle', 'Alt başlık (kurum · yıl)', 'text', false],
                ]),
            ]),
        ]);
    }
}
