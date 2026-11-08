<x-layouts.app :title="__('site.privacy.title')">
    <article class="card mt-[calc(clamp(20px,2.4vw,34px)-clamp(96px,11vw,150px))] flex flex-col gap-6 rounded-[36px] p-[clamp(22px,5vw,56px)]">
        <span class="eyebrow"><b></b>{{ config('site.name') }}</span>
        <h1 class="h2">{{ __('site.privacy.title') }}</h1>
        <p class="m-0 max-w-[70ch] leading-[1.8] text-c3">{{ __('site.privacy.body') }}</p>
        <a class="btn glass btn-sm self-start text-c1" href="{{ localized_route('home') }}">{{ __('site.case.back') }}</a>
    </article>
</x-layouts.app>
