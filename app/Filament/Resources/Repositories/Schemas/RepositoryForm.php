<?php

namespace App\Filament\Resources\Repositories\Schemas;

use App\Filament\Support\Translatable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RepositoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Genel')->columns(2)->columnSpanFull()->schema([
                TextInput::make('name')->label('Repo adı (kullanıcı/paket)')->required(),
                TextInput::make('language')->label('Dil')->default('PHP'),
                TextInput::make('url')->label('GitHub adresi')->url(),
                TextInput::make('stars')->label('Yıldız sayısı')->numeric(),
                TextInput::make('sort')->label('Sıra')->numeric()->default(0),
                Toggle::make('is_published')->label('Yayında')->default(true),
            ]),
            Section::make('Metinler')->description('Türkçe zorunlu; İngilizce boş kalırsa sitede Türkçe gösterilir.')->columnSpanFull()->schema([
                Translatable::tabs([
                    ['description', 'Açıklama', 'textarea', true],
                    ['kind', 'Tür (ör. Laravel paketi)', 'text', true],
                ]),
            ]),
        ]);
    }
}
