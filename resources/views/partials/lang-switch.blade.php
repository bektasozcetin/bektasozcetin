<div role="group" aria-label="{{ __('site.nav.lang') }}" class="flex gap-0.5 rounded-full {{ $class ?? '' }}">
    @foreach (['tr' => 'Türkçe', 'en' => 'English'] as $code => $name)
        <a href="{{ alternate_url($code) }}" hreflang="{{ $code }}" lang="{{ $code }}" class="lang-btn" aria-label="{{ $name }}" @if (app()->getLocale() === $code) aria-current="true" @endif>{{ strtoupper($code) }}</a>
    @endforeach
</div>
