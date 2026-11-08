<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

/**
 * Sitenin tasarımına uygun giriş sayfası.
 * Giriş mantığı (doğrulama, hız sınırı, "beni hatırla") Filament'in kendi sınıfından gelir;
 * burada yalnızca görünüm değişir.
 */
class Login extends BaseLogin
{
    protected static string $layout = 'filament.auth.layout';

    protected string $view = 'filament.auth.login';

    public function getTitle(): string|Htmlable
    {
        return 'Giriş';
    }

    public function getHeading(): string|Htmlable|null
    {
        return null;
    }

    public function hasLogo(): bool
    {
        return false;
    }
}
