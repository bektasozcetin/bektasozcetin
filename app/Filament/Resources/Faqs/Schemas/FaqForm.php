<?php

namespace App\Filament\Resources\Faqs\Schemas;

use App\Filament\Support\Translatable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Genel')->columns(2)->columnSpanFull()->schema([
                TextInput::make('sort')->label('Sıra')->numeric()->default(0),
                Toggle::make('is_published')->label('Yayında')->default(true),
            ]),
            Section::make('Metinler')->description('Türkçe zorunlu; İngilizce boş kalırsa sitede Türkçe gösterilir.')->columnSpanFull()->schema([
                Translatable::tabs([
                    ['question', 'Soru', 'text', true],
                    ['answer', 'Cevap', 'textarea', true],
                ]),
            ]),
        ]);
    }
}
