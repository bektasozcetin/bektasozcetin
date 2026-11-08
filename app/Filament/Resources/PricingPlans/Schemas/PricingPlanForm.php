<?php

namespace App\Filament\Resources\PricingPlans\Schemas;

use App\Filament\Support\Translatable;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PricingPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Genel')->columns(2)->columnSpanFull()->schema([
                Select::make('contact_type')->label('Formda seçilecek proje türü')->required()->options(__('site.contact.types')),
                TextInput::make('sort')->label('Sıra')->numeric()->default(0),
                Toggle::make('is_featured')->label('Öne çıkar (mavi buton)'),
            ]),
            Section::make('Metinler')->description('Türkçe zorunlu; İngilizce boş kalırsa sitede Türkçe gösterilir.')->columnSpanFull()->schema([
                Translatable::tabs([
                    ['title', 'Başlık', 'text', true],
                    ['badge', 'Rozet (ör. Uzun vadeli)', 'text', false],
                    ['description', 'Açıklama', 'textarea', true],
                    ['price_label', 'Fiyat etiketi (Başlangıç, Aylık…)', 'text', true],
                    ['price', 'Fiyat', 'text', true],
                    ['cta', 'Buton metni', 'text', true],
                ]),
                Translatable::list('items', 'Maddeler'),
            ]),
        ]);
    }
}
