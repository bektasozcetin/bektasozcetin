<?php

namespace App\Filament\Resources\Experiences\Schemas;

use App\Filament\Support\Translatable;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExperienceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Genel')->columns(2)->columnSpanFull()->schema([
                TextInput::make('role')->label('Pozisyon')->required(),
                TextInput::make('sort')->label('Sıra')->numeric()->default(0),
                TagsInput::make('tags')->label('Teknolojiler')->columnSpanFull(),
                Toggle::make('is_current')->label('Halen burada çalışıyorum'),
            ]),
            Section::make('Metinler')->description('Türkçe zorunlu; İngilizce boş kalırsa sitede Türkçe gösterilir.')->columnSpanFull()->schema([
                Translatable::tabs([
                    ['period', 'Dönem (ör. 2024 — Bugün)', 'text', true],
                    ['company', 'Şirket', 'text', true],
                    ['description', 'Açıklama', 'textarea', true],
                ]),
            ]),
        ]);
    }
}
