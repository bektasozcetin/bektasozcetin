{{-- Bölüm başlığı: etiket + başlık + açıklama. Sağ tarafa özel içerik slot ile verilebilir. --}}
{{-- level: alt sayfalarda ilk bölüm h1 olur, ana sayfada hepsi h2 kalır. --}}
@props(['eyebrow', 'a', 'b', 'lede' => null, 'serif' => false, 'stack' => false, 'level' => 'h2'])
<div {{ $attributes->class(['reveal flex gap-6', 'flex-col' => $stack, 'flex-wrap items-end justify-between' => ! $stack]) }}>
    <div class="flex min-w-0 flex-col gap-[18px]">
        <span class="eyebrow"><b></b>{{ $eyebrow }}</span>
        <{{ $level }} class="h2">{{ $a }} <span @class(['grad-static', 'serif text-[1.1em]' => $serif])>{{ $b }}</span></{{ $level }}>
        @if ($stack && $lede)
            <p class="lede">{{ $lede }}</p>
        @endif
        {{ $below ?? '' }}
    </div>
    @unless ($stack)
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @elseif ($lede)
            <p class="lede">{{ $lede }}</p>
        @endif
    @endunless
</div>
