<?php

namespace App\Filament\Resources\Repositories\Pages;

use App\Filament\Resources\Repositories\RepositoryResource;
use App\Support\GitHubSync;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListRepositories extends ListRecords
{
    protected static string $resource = RepositoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sync')
                ->label("GitHub'dan eşitle")
                ->icon(Heroicon::ArrowPath)
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading("GitHub'dan eşitle")
                ->modalDescription(fn () => ($user = GitHubSync::username())
                    ? "github.com/{$user} hesabındaki açık repolar çekilecek. Yeni repolar gizli eklenir; elle yazdığın açıklamalar korunur."
                    : 'Önce Site ayarları → GitHub bölümünden kullanıcı adını gir.')
                ->modalSubmitActionLabel('Eşitle')
                ->action(function () {
                    try {
                        $r = GitHubSync::run();
                        Notification::make()->success()->title('GitHub eşitlendi')
                            ->body("{$r['created']} yeni, {$r['updated']} güncellendi, {$r['skipped']} atlandı (fork/arşiv).")->send();
                    } catch (\Throwable $e) {
                        Notification::make()->danger()->title('Eşitleme yapılamadı')->body($e->getMessage())->send();
                    }
                }),
            CreateAction::make(),
        ];
    }
}
