<x-filament-widgets::widget>
    <div class="bk-welcome">
        <div class="bk-welcome-main">
            <span class="bk-eyebrow"><i></i>{{ $date }}</span>
            <h2 class="bk-welcome-title">{{ $greeting }}@if ($name), <span class="bk-serif">{{ $name }}.</span>@else.@endif</h2>
            <p class="bk-welcome-sub">
                <span><span class="bk-dot"></span>Bugün <b>{{ $today }}</b> görüşme</span>
                <span><b>{{ $pending }}</b> görüşme onay bekliyor</span>
                <span><b>{{ $unread }}</b> okunmamış mesaj</span>
            </p>
        </div>
        <div class="bk-welcome-actions">
            <x-filament::button tag="a" :href="\App\Filament\Resources\Posts\PostResource::getUrl('create')" icon="heroicon-m-pencil-square">Yeni not</x-filament::button>
            <x-filament::button tag="a" :href="\App\Filament\Resources\Projects\ProjectResource::getUrl('create')" icon="heroicon-m-plus" color="gray">Yeni proje</x-filament::button>
            <x-filament::button tag="a" :href="\App\Filament\Resources\Meetings\MeetingResource::getUrl()" icon="heroicon-m-calendar-days" color="gray">Takvim</x-filament::button>
        </div>
    </div>
</x-filament-widgets::widget>
