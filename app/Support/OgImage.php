<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Paylaşım görseli (1200×630) üretir: WhatsApp, LinkedIn, X önizlemeleri için.
 * Görsel bir kez üretilir ve storage/app/public/og altında saklanır;
 * başlık, görsel ya da kayıt değişince yeni dosya üretilir.
 */
class OgImage
{
    private const W = 1200;

    private const H = 630;

    /**
     * @param  string  $kicker  Üstteki küçük etiket (ör. "VAKA ÇALIŞMASI · 2026")
     * @param  string  $title  Büyük başlık
     * @param  string|null  $subtitle  Başlık altı italik satır
     * @param  string|null  $image  Sağ taraftaki görselin tam dosya yolu
     */
    public static function make(string $kicker, string $title, ?string $subtitle = null, ?string $image = null, string $version = ''): string
    {
        $key = md5(implode('|', [$kicker, $title, $subtitle, $image, $image && is_file($image) ? filemtime($image) : '', $version, 'v3']));
        $path = "og/{$key}.png";
        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            try {
                $disk->put($path, self::render($kicker, $title, $subtitle, $image));
            } catch (\Throwable $e) {
                report($e);

                return asset('images/portrait.jpg');
            }
        }

        return asset('storage/'.$path);
    }

    private static function render(string $kicker, string $title, ?string $subtitle, ?string $image): string
    {
        $font = fn (string $name) => self::font($name);
        $im = imagecreatetruecolor(self::W, self::H);
        imagealphablending($im, true);

        $bg = imagecolorallocate($im, 20, 21, 24);
        imagefilledrectangle($im, 0, 0, self::W, self::H, $bg);

        // Sağ panel: görsel, sola doğru zemine eriyen geçişle
        $panelX = 560;
        if ($image && is_file($image) && ($src = @imagecreatefromstring(file_get_contents($image)))) {
            $sw = imagesx($src);
            $sh = imagesy($src);
            $tw = self::W - $panelX;
            $scale = max($tw / $sw, self::H / $sh);
            $cw = (int) ($tw / $scale);
            $ch = (int) (self::H / $scale);
            imagecopyresampled($im, $src, $panelX, 0, (int) (($sw - $cw) / 2), (int) (($sh - $ch) / 2), $tw, self::H, $cw, $ch);
            imagedestroy($src);

            // Hafif koyulaştırma + mavi ton
            imagefilledrectangle($im, $panelX, 0, self::W, self::H, imagecolorallocatealpha($im, 20, 21, 24, 70));
            imagefilledrectangle($im, $panelX, 0, self::W, self::H, imagecolorallocatealpha($im, 47, 107, 255, 118));
            for ($x = 0; $x < 260; $x++) {
                $alpha = (int) (127 * ($x / 260) ** 1.4);
                imageline($im, $panelX + $x, 0, $panelX + $x, self::H, imagecolorallocatealpha($im, 20, 21, 24, $alpha));
            }
        } else {
            // Görsel yoksa mavi ışık halkası
            for ($r = 420; $r > 0; $r -= 6) {
                $alpha = (int) (127 - 34 * (1 - $r / 420) ** 2);
                imagefilledellipse($im, 1010, 140, $r * 2, $r * 2, imagecolorallocatealpha($im, 47, 107, 255, $alpha));
            }
        }

        // Nokta deseni (sol üst)
        $dot = imagecolorallocatealpha($im, 255, 255, 255, 112);
        for ($y = 28; $y < 230; $y += 28) {
            for ($x = 28; $x < 520; $x += 28) {
                imagefilledellipse($im, $x, $y, 2, 2, $dot);
            }
        }

        $white = imagecolorallocate($im, 245, 245, 242);
        $muted = imagecolorallocate($im, 168, 170, 176);
        $blue = imagecolorallocate($im, 79, 132, 255);

        // Üst etiket
        imagefilledellipse($im, 78, 92, 12, 12, $blue);
        imagettftext($im, 15, 0, 96, 99, $muted, $font('IBMPlexMono-Medium'), mb_strtoupper($kicker, 'UTF-8'));

        // Başlık: sığana kadar küçült, en fazla 3 satır
        $maxWidth = ($image ? $panelX + 60 : self::W - 150) - 72;
        foreach ([66, 60, 54, 48, 42] as $size) {
            $lines = self::wrap($title, $font('Manrope-ExtraBold'), $size, $maxWidth);
            if (count($lines) <= 3) {
                break;
            }
        }
        $lines = array_slice($lines, 0, 3);
        $lineHeight = (int) ($size * 1.12);
        $y = ($subtitle ? 270 : 300) - (int) ((count($lines) - 1) * $lineHeight / 2);
        foreach ($lines as $line) {
            imagettftext($im, $size, 0, 72, $y, $white, $font('Manrope-ExtraBold'), $line);
            $y += $lineHeight;
        }

        if ($subtitle) {
            $y += 12;
            foreach (array_slice(self::wrap($subtitle, $font('PlayfairDisplay-Italic'), 25, $maxWidth - 20), 0, 2) as $sub) {
                imagettftext($im, 25, 0, 74, $y, $muted, $font('PlayfairDisplay-Italic'), $sub);
                $y += 36;
            }
        }

        // Alt satır
        imageline($im, 72, 528, $image ? $panelX : self::W - 72, 528, imagecolorallocatealpha($im, 255, 255, 255, 105));
        imagettftext($im, 20, 0, 72, 572, $white, $font('Manrope-ExtraBold'), 'bektas');
        $box = imagettfbbox(20, 0, $font('Manrope-ExtraBold'), 'bektas');
        imagettftext($im, 23, 0, 72 + $box[2] + 1, 572, $blue, $font('PlayfairDisplay-Italic'), '.dev');
        $name = $image ? 'Full Stack Laravel' : config('site.name').' · Full Stack Laravel';
        $box = imagettfbbox(14, 0, $font('IBMPlexMono-Medium'), $name);
        imagettftext($im, 14, 0, ($image ? $panelX : self::W - 72) - $box[2], 570, $muted, $font('IBMPlexMono-Medium'), $name);

        ob_start();
        imagepng($im, null, 8);
        imagedestroy($im);

        return ob_get_clean();
    }

    /**
     * FreeType, Windows'ta ASCII dışı karakter içeren yolları (ör. "Yeni klasör") açamıyor;
     * bu durumda fontu geçici klasöre kopyalayıp oradan kullanır.
     */
    private static function font(string $name): string
    {
        $path = resource_path("fonts/{$name}.ttf");
        if (mb_check_encoding($path, 'ASCII')) {
            return $path;
        }

        $copy = sys_get_temp_dir().DIRECTORY_SEPARATOR.'og-fonts'.DIRECTORY_SEPARATOR.$name.'.ttf';
        if (! is_file($copy) || filesize($copy) !== filesize($path)) {
            @mkdir(dirname($copy), 0777, true);
            copy($path, $copy);
        }

        return $copy;
    }

    /** Metni verilen genişliğe sığacak satırlara böler. */
    private static function wrap(string $text, string $font, int $size, int $maxWidth): array
    {
        $lines = [];
        $line = '';
        foreach (preg_split('/\s+/u', trim($text)) as $word) {
            $try = $line === '' ? $word : "{$line} {$word}";
            $box = imagettfbbox($size, 0, $font, $try);
            if ($box[2] - $box[0] > $maxWidth && $line !== '') {
                $lines[] = $line;
                $line = $word;
            } else {
                $line = $try;
            }
        }
        if ($line !== '') {
            $lines[] = $line;
        }

        return $lines;
    }
}
