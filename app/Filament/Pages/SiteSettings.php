<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * Site ayarları: CV dosyaları, görüşme takvimi ve ziyaretçi istatistiği.
 */
class SiteSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Ayarlar';

    protected static ?string $navigationLabel = 'Site ayarları';

    protected static ?string $title = 'Site ayarları';

    protected static ?string $slug = 'ayarlar';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Setting::allValues());
    }

    public function form(Schema $schema): Schema
    {
        return $schema->statePath('data')->components([
            Section::make('CV')->description('Kapakta "CV indir" bağlantısı olarak görünür. İngilizce dosya yoksa Türkçesi kullanılır.')->columns(2)->schema([
                FileUpload::make('cv_tr')->label('CV (Türkçe, PDF)')->disk('public')->directory('cv')->acceptedFileTypes(['application/pdf'])->maxSize(5120)->downloadable(),
                FileUpload::make('cv_en')->label('CV (English, PDF)')->disk('public')->directory('cv')->acceptedFileTypes(['application/pdf'])->maxSize(5120)->downloadable(),
            ]),

            Section::make('Görüşme takvimi')->description('"Görüşme planla" butonları ziyaretçiye uygun saatleri gösterir; talepler Gelen kutusu → Görüşme takvimi altına düşer.')->columns(2)->schema([
                Toggle::make('booking_enabled')->label('Site içi takvim açık')->columnSpanFull()
                    ->helperText('Kapalıysa butonlar iletişim formuna gider.'),
                CheckboxList::make('booking_days')->label('Görüşme günleri')->columns(4)->columnSpanFull()
                    ->options(['1' => 'Pazartesi', '2' => 'Salı', '3' => 'Çarşamba', '4' => 'Perşembe', '5' => 'Cuma', '6' => 'Cumartesi', '0' => 'Pazar']),
                TagsInput::make('booking_times')->label('Saatler (Türkiye saati)')->placeholder('ör. 10:00')->columnSpanFull()
                    ->nestedRecursiveRules(['regex:/^([01]\d|2[0-3]):[0-5]\d$/'])
                    ->helperText('SS:DD biçiminde yaz, her saat ayrı bir etiket.'),
                TextInput::make('booking_duration')->label('Görüşme süresi (dk)')->numeric()->minValue(15)->maxValue(120)->required(),
                TextInput::make('booking_horizon')->label('Kaç gün ilerisi gösterilsin')->numeric()->minValue(3)->maxValue(60)->required(),
                TextInput::make('booking_notice_hours')->label('En az kaç saat önceden')->numeric()->minValue(0)->maxValue(168)->required(),
                TextInput::make('booking_url')->label('Harici takvim adresi (isteğe bağlı)')->url()->placeholder('https://cal.com/kullanici-adin/30dk')
                    ->helperText('Doldurursan site içi takvim yerine bu adres yeni sekmede açılır (Cal.com, Calendly…).'),
            ]),

            Section::make('GitHub')->description('Panel → Açık Kaynak → "GitHub\'dan eşitle" ile repoların yıldız, fork ve güncellenme bilgisi çekilir; her gece otomatik tazelenir.')->schema([
                TextInput::make('github_username')->label('GitHub kullanıcı adı')->placeholder('bektasozcetin')->prefix('github.com/')
                    ->regex('/^[A-Za-z0-9-]{1,39}$/')
                    ->helperText('Boş bırakırsan .env içindeki SITE_GITHUB_URL adresinden okunur.'),
            ]),

            Section::make('Ziyaretçi istatistiği')->description('Proje ve notların okunma sayıları her zaman panelde tutulur. Ayrıntılı istatistik için bir servis bağlayabilirsin.')->columns(2)->schema([
                Radio::make('analytics_provider')->label('Servis')->live()->columnSpanFull()->options([
                    'none' => 'Yok (yalnızca paneldeki sayaçlar)',
                    'plausible' => 'Plausible: çerezsiz, KVKK için onay bandı gerekmez',
                    'ga4' => 'Google Analytics 4: çerez kullanır, ziyaretçiye onay bandı gösterilir',
                ]),
                TextInput::make('plausible_domain')->label('Plausible alan adı')->placeholder('bektas.dev')
                    ->visible(fn (Get $get) => $get('analytics_provider') === 'plausible')->required(fn (Get $get) => $get('analytics_provider') === 'plausible'),
                TextInput::make('plausible_src')->label('Plausible betik adresi')->url()
                    ->visible(fn (Get $get) => $get('analytics_provider') === 'plausible'),
                TextInput::make('ga_id')->label('GA4 ölçüm kimliği')->placeholder('G-XXXXXXXXXX')->regex('/^G-[A-Z0-9]{4,}$/')
                    ->visible(fn (Get $get) => $get('analytics_provider') === 'ga4')->required(fn (Get $get) => $get('analytics_provider') === 'ga4'),
            ]),
        ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('save')->label('Kaydet')->submit('save')->keyBindings(['mod+s']),
                    ]),
                ]),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $data['booking_times'] = collect($data['booking_times'] ?? [])->unique()->sort()->values()->all();
        $data['booking_duration'] = (int) $data['booking_duration'];
        $data['booking_horizon'] = (int) $data['booking_horizon'];
        $data['booking_notice_hours'] = (int) $data['booking_notice_hours'];

        Setting::put(array_intersect_key($data, Setting::DEFAULTS));

        Notification::make()->title('Ayarlar kaydedildi')->success()->send();
    }
}
