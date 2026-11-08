<?php

namespace App\Models\Concerns;

/**
 * JSON olarak tutulan {"tr": "...", "en": "..."} alanlarını aktif dile göre okur.
 * Aktif dilde değer yoksa Türkçeye düşer.
 */
trait HasTranslations
{
    public function t(string $attribute): ?string
    {
        return static::pick($this->getAttribute($attribute));
    }

    /** Tek bir {tr, en} dizisinden aktif dildeki metni seçer. */
    public static function pick(mixed $value): ?string
    {
        if (! is_array($value)) {
            return $value;
        }

        $locale = app()->getLocale();

        return filled($value[$locale] ?? null) ? $value[$locale] : ($value['tr'] ?? null);
    }

    /** [{tr, en}, ...] listesini aktif dildeki metinlere çevirir. */
    public function tList(string $attribute): array
    {
        return array_values(array_filter(array_map(
            fn ($item) => static::pick($item),
            $this->getAttribute($attribute) ?? []
        )));
    }

    protected function initializeHasTranslations(): void
    {
        foreach ($this->translatable ?? [] as $attribute) {
            $this->casts[$attribute] ??= 'array';
        }
    }
}
