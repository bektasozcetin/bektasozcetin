<?php

namespace App\Filament\Support;

use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

/**
 * İki dilli (JSON {tr, en}) alanlar için form yardımcıları.
 *
 * Alan tanımı: ['alan', 'Etiket', 'text'|'textarea', zorunlu_mu]
 * Türkçe alanlar zorunlu olabilir; İngilizce boş kalırsa sitede Türkçe gösterilir.
 */
class Translatable
{
    public const LOCALES = ['tr' => 'Türkçe', 'en' => 'English'];

    /** Türkçe / English sekmeleri. */
    public static function tabs(array $fields): Tabs
    {
        return Tabs::make('Diller')
            ->columnSpanFull()
            ->tabs(collect(self::LOCALES)->map(
                fn (string $label, string $locale) => Tab::make($label)->schema(self::fields($locale, $fields))
            )->values()->all());
    }

    public static function fields(string $locale, array $fields): array
    {
        return collect($fields)->map(function (array $field) use ($locale) {
            [$name, $label, $type, $required] = $field + [2 => 'text', 3 => false];

            $component = match ($type) {
                'textarea' => Textarea::make("{$name}.{$locale}")->rows(3)->autosize(),
                'markdown' => MarkdownEditor::make("{$name}.{$locale}")
                    ->toolbarButtons([['heading', 'bold', 'italic', 'link'], ['blockquote', 'codeBlock', 'bulletList', 'orderedList'], ['undo', 'redo']]),
                default => TextInput::make("{$name}.{$locale}")->maxLength(255),
            };

            return $component
                ->label($label)
                ->required($required && $locale === 'tr')
                ->helperText($locale === 'en' && $required ? 'Boş bırakılırsa Türkçe metin gösterilir.' : null);
        })->all();
    }

    /** [{tr, en}, ...] biçimindeki liste (ör. fiyat maddeleri). */
    public static function list(string $name, string $label): Repeater
    {
        return Repeater::make($name)
            ->label($label)
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('tr')->label('Türkçe')->required(),
                    TextInput::make('en')->label('English'),
                ]),
            ])
            ->defaultItems(0)
            ->reorderable()
            ->collapsible()
            ->columnSpanFull();
    }
}
