<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use App\Filament\Support\Translatable;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Genel')->columns(2)->columnSpanFull()->schema([
                TextInput::make('name')->label('Ad Soyad')->required(),
                TextInput::make('sort')->label('Sıra')->numeric()->default(0),
                FileUpload::make('photo')->label('Fotoğraf')->image()->avatar()->disk('public')->directory('testimonials'),
                Toggle::make('is_published')->label('Yayında')->default(true),
            ]),
            Section::make('Metinler')->description('Türkçe zorunlu; İngilizce boş kalırsa sitede Türkçe gösterilir.')->columnSpanFull()->schema([
                Translatable::tabs([
                    ['quote', 'Yorum', 'textarea', true],
                    ['title', 'Ünvan · Şirket', 'text', true],
                ]),
            ]),
        ]);
    }
}
