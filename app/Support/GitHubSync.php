<?php

namespace App\Support;

use App\Models\Repository;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * GitHub'daki açık repoları "Açık kaynak" listesine eşitler.
 * - Var olan repoların yıldız, fork, dil ve güncellenme bilgisi tazelenir; elle yazılmış açıklamalar korunur.
 * - Yeni repolar gizli (yayında değil) olarak eklenir; hangisinin sitede görüneceğini panelden seçersin.
 * - Fork'lar ve arşivlenmiş repolar atlanır. Elle eklenen kayıtlar silinmez.
 */
class GitHubSync
{
    public static function username(): ?string
    {
        if ($name = trim((string) setting('github_username'))) {
            return ltrim($name, '@');
        }

        $url = config('site.social.GitHub');
        if ($url && preg_match('~github\.com/([A-Za-z0-9-]+)~', $url, $m)) {
            return $m[1];
        }

        return null;
    }

    /** @return array{created: int, updated: int, skipped: int} */
    public static function run(?string $username = null): array
    {
        $username ??= self::username();
        if (! $username) {
            throw new RuntimeException('GitHub kullanıcı adı ayarlı değil (Site ayarları → GitHub).');
        }

        $response = Http::withHeaders(['Accept' => 'application/vnd.github+json', 'User-Agent' => 'bektas.dev-portfolio'])
            ->timeout(15)
            ->get("https://api.github.com/users/{$username}/repos", ['per_page' => 100, 'sort' => 'pushed', 'type' => 'owner']);

        if ($response->status() === 404) {
            throw new RuntimeException("GitHub'da \"{$username}\" adında bir kullanıcı bulunamadı.");
        }
        if ($response->status() === 403) {
            throw new RuntimeException('GitHub istek sınırı doldu. Biraz sonra tekrar dene.');
        }
        $response->throw();

        $result = ['created' => 0, 'updated' => 0, 'skipped' => 0];
        $nextSort = (int) Repository::max('sort') + 1;

        foreach ($response->json() as $repo) {
            if ($repo['fork'] || $repo['archived'] || $repo['private']) {
                $result['skipped']++;

                continue;
            }

            $record = Repository::where('github_id', $repo['id'])
                ->orWhere('name', $repo['full_name'])
                ->orWhere('url', $repo['html_url'])
                ->first();

            $data = [
                'github_id' => $repo['id'],
                'language' => $repo['language'] ?: ($record->language ?? 'PHP'),
                'url' => $repo['html_url'],
                'stars' => $repo['stargazers_count'],
                'forks' => $repo['forks_count'],
                'topics' => $repo['topics'] ?? [],
                'pushed_at' => $repo['pushed_at'],
            ];

            if ($record) {
                // Elle yazılmış açıklama varsa dokunma
                if (blank($record->description['tr'] ?? null) && filled($repo['description'])) {
                    $data['description'] = ['tr' => $repo['description'], 'en' => $repo['description']];
                }
                $record->update($data);
                $result['updated']++;
            } else {
                Repository::create($data + [
                    'name' => $repo['full_name'],
                    'description' => ['tr' => $repo['description'] ?? '', 'en' => $repo['description'] ?? ''],
                    'kind' => ['tr' => 'GitHub reposu', 'en' => 'GitHub repository'],
                    'is_published' => false,
                    'sort' => $nextSort++,
                ]);
                $result['created']++;
            }
        }

        return $result;
    }
}
