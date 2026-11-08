<?php

namespace App\Filament\Resources\Posts;

use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Filament\Support\Translatable;
use App\Models\Post;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPencilSquare;

    protected static string|UnitEnum|null $navigationGroup = 'İçerik';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Not';

    protected static ?string $pluralModelLabel = 'Notlar (blog)';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Genel')->columns(2)->columnSpanFull()->schema([
                TextInput::make('slug')->label('Adres (slug)')->required()->unique(ignoreRecord: true)->alphaDash()
                    ->helperText('Yazının adresi: /notlar/{slug}'),
                DateTimePicker::make('published_at')->label('Yayın tarihi')->seconds(false)
                    ->helperText('Boş bırakırsan taslak kalır; ileri bir tarih verirsen o gün yayına girer.'),
                TagsInput::make('tags')->label('Etiketler')->columnSpanFull(),
                FileUpload::make('cover')->label('Kapak görseli (isteğe bağlı, 16:9)')->image()->disk('public')->directory('posts')->columnSpanFull(),
            ]),
            Section::make('Metin')->description('Gövde Markdown ile yazılır. İngilizce boş kalırsa sitede Türkçe gösterilir.')->columnSpanFull()->schema([
                Translatable::tabs([
                    ['title', 'Başlık', 'text', true],
                    ['excerpt', 'Özet (listede ve paylaşımda görünür)', 'textarea', true],
                    ['body', 'Gövde', 'markdown', true],
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                TextColumn::make('title')->label('Başlık')->getStateUsing(fn ($record) => $record->t('title'))->weight('bold')->wrap(),
                TextColumn::make('status')->label('Durum')->badge()
                    ->getStateUsing(fn ($record) => $record->published_at === null ? 'Taslak' : ($record->isPublished() ? 'Yayında' : 'Zamanlandı'))
                    ->color(fn ($state) => match ($state) { 'Yayında' => 'success', 'Zamanlandı' => 'warning', default => 'gray' }),
                TextColumn::make('published_at')->label('Tarih')->date('d.m.Y')->sortable(),
                TextColumn::make('views')->label('Okunma')->numeric()->sortable(),
            ])
            ->recordActions([
                Action::make('open')->label('Sitede aç')->icon(Heroicon::ArrowTopRightOnSquare)
                    ->url(fn ($record) => $record->url('tr'))->openUrlInNewTab()->visible(fn ($record) => $record->isPublished()),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
