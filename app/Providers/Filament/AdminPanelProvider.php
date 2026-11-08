<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Dashboard;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->brandName('bektas.dev')
            ->brandLogo(fn () => view('filament.brand'))
            ->brandLogoHeight('2.4rem')
            ->favicon(asset('favicon.svg'))
            ->font('Manrope')
            ->colors([
                'primary' => Color::hex('#2F6BFF'),
                // Sitenin grafit tonları
                'gray' => [
                    50 => 'oklch(0.979 0.003 106.4)',
                    100 => 'oklch(0.958 0.003 106.4)',
                    200 => 'oklch(0.922 0.004 271.4)',
                    300 => 'oklch(0.842 0.009 271.3)',
                    400 => 'oklch(0.696 0.014 271.2)',
                    500 => 'oklch(0.549 0.014 271.1)',
                    600 => 'oklch(0.399 0.012 273.1)',
                    700 => 'oklch(0.310 0.011 271.0)',
                    800 => 'oklch(0.236 0.008 274.6)',
                    900 => 'oklch(0.196 0.006 271.1)',
                    950 => 'oklch(0.169 0.006 271.0)',
                ],
            ])
            ->defaultThemeMode(ThemeMode::Dark)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->sidebarCollapsibleOnDesktop()
            ->renderHook(PanelsRenderHook::HEAD_END, fn () => view('filament.hooks.head'))
            ->renderHook(PanelsRenderHook::USER_MENU_BEFORE, fn () => view('filament.hooks.site-link'))
            ->navigationGroups(['Gelen kutusu', 'İçerik', 'Ayarlar'])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
