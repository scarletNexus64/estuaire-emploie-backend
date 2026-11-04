<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RuntimeException;
use Throwable;

class UpdateVerifiedCompanyLinks extends Command
{
    protected $signature = 'companies:update-verified-links {file : Manifeste JSON des liens vérifiés} {--apply : Appliquer les changements (simulation par défaut)}';

    protected $description = 'Met à jour les liens vérifiés des entreprises existantes, avec contrôle d’identité et sauvegarde privée.';

    public static function validLink(string $field, string $url): bool
    {
        if (!in_array($field, ['website', 'facebook', 'tiktok'], true)
            || strlen($url) > 255 || !filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }
        $parts = parse_url($url);
        $host = strtolower($parts['host'] ?? '');
        $path = $parts['path'] ?? '/';
        if (!in_array($parts['scheme'] ?? '', ['https', 'http'], true)
            || isset($parts['user']) || isset($parts['pass']) || isset($parts['port'])
            || preg_match('~/(search|discover|login|share|sharer)(/|\.|$)~i', $path)
            || preg_match('~(^|\.)(google\.[a-z.]+|bing\.com|duckduckgo\.com|business\.site)$~i', $host)) {
            return false;
        }
        if ($field === 'facebook') {
            return in_array($host, ['facebook.com', 'www.facebook.com', 'web.facebook.com', 'm.facebook.com'], true)
                && (bool) preg_match('~^/(?:[A-Za-z0-9._-]+/?|people/[^/]+/\d+/?|profile\.php)$~', $path)
                && !in_array(trim($path, '/'), ['groups', 'watch', 'reel', 'events', 'pages', 'photo.php', 'story.php'], true)
                && ($path !== '/profile.php' || preg_match('~^id=\d+$~', $parts['query'] ?? '') === 1);
        }
        if ($field === 'tiktok') {
            return in_array($host, ['tiktok.com', 'www.tiktok.com'], true)
                && preg_match('~^/@[A-Za-z0-9._]+/?$~', $path) === 1;
        }

        return !preg_match('~(^|\.)(facebook\.com|tiktok\.com|instagram\.com|linkedin\.com|tripadvisor\.[a-z.]+)$~i', $host);
    }

    public function handle(): int
    {
        try {
            $file = $this->argument('file');
            $data = json_decode(File::get($file), true, 512, JSON_THROW_ON_ERROR);
            if (($data['schema_version'] ?? null) !== 1 || !is_array($data['entries'] ?? null)) {
                throw new RuntimeException('Format du manifeste invalide.');
            }
            $seen = [];
            foreach ($data['entries'] as $entry) {
                if (!is_int($entry['company_id'] ?? null) || empty($entry['name']) || empty($entry['city'])
                    || empty($entry['links']) || isset($seen[$entry['company_id']])) {
                    throw new RuntimeException('Identité absente ou entreprise dupliquée dans le manifeste.');
                }
                $seen[$entry['company_id']] = true;
                foreach ($entry['links'] as $field => $link) {
                    if (!is_array($link) || !self::validLink($field, $link['url'] ?? '')
                        || empty($link['verified_at']) || empty($link['evidence']) || empty($link['reason'])) {
                        throw new RuntimeException('Lien invalide ou preuve absente : '.$entry['name'].' / '.$field);
                    }
                    foreach ($link['evidence'] as $source) {
                        if (!is_string($source) || !filter_var($source, FILTER_VALIDATE_URL)
                            || !in_array(parse_url($source, PHP_URL_SCHEME), ['http', 'https'], true)) {
                            throw new RuntimeException('URL de preuve invalide.');
                        }
                    }
                }
            }
            $root = storage_path('app/private/company-link-updates');
            File::ensureDirectoryExists($root, 0700);
            $report = ['source_sha256' => hash_file('sha256', $file), 'apply' => (bool) $this->option('apply'), 'changes' => [], 'preserved' => [], 'unchanged' => 0];
            $reportPath = $root.'/'.now()->format('Ymd-His').'-'.bin2hex(random_bytes(4)).'.json';
            DB::transaction(function () use ($data, &$report, $reportPath) {
                foreach ($data['entries'] as $entry) {
                    $company = DB::table('companies')->where('id', $entry['company_id'])->whereNull('deleted_at')->lockForUpdate()->first();
                    if (!$company || $company->name !== $entry['name'] || $company->city !== $entry['city']
                        || ($company->phone ?? null) !== ($entry['phone'] ?? null)) {
                        throw new RuntimeException('Identité modifiée ou absente : '.$entry['company_id']);
                    }
                    $updates = [];
                    $before = [];
                    foreach ($entry['links'] as $field => $link) {
                        $old = $company->{$field};
                        if ($old === $link['url']) {
                            $report['unchanged']++;
                            continue;
                        }
                        // Les liens renseignés par les entreprises sont conservés pour revue séparée.
                        if ($old !== null && trim($old) !== '') {
                            $report['preserved'][] = ['company_id' => $company->id, 'field' => $field, 'existing' => $old, 'candidate' => $link['url']];
                            continue;
                        }
                        $before[$field] = $old;
                        $updates[$field] = $link['url'];
                    }
                    if ($updates) {
                        $report['changes'][] = ['company_id' => $company->id, 'name' => $company->name, 'city' => $company->city, 'before' => $before, 'after' => $updates, 'previous_updated_at' => $company->updated_at];
                    }
                }
                // Rapport et anciennes valeurs enregistrés avant toute écriture, même en simulation.
                $json = json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
                $handle = fopen($reportPath, 'x');
                if (!$handle) {
                    throw new RuntimeException('Impossible de créer la sauvegarde privée.');
                }
                try {
                    if (!chmod($reportPath, 0600) || fwrite($handle, $json) !== strlen($json) || !fflush($handle)) {
                        throw new RuntimeException('Sauvegarde incomplète : mise à jour annulée.');
                    }
                } finally {
                    fclose($handle);
                }
                if ($this->option('apply')) {
                    foreach ($report['changes'] as $change) {
                        DB::table('companies')->where('id', $change['company_id'])->update($change['after'] + ['updated_at' => now()]);
                        $after = DB::table('companies')->where('id', $change['company_id'])->first();
                        foreach ($change['after'] as $field => $url) {
                            if ($after->{$field} !== $url) {
                                throw new RuntimeException('Échec de la vérification après écriture.');
                            }
                        }
                    }
                }
            });
            $this->info(count($report['changes']).' entreprises ; '.array_sum(array_map(fn ($c) => count($c['after']), $report['changes'])).' liens '.($this->option('apply') ? 'mis à jour' : 'prévus').'.');
            $this->line(count($report['preserved']).' valeurs existantes préservées ; '.$report['unchanged'].' liens déjà identiques.');
            $this->line('Sauvegarde / rapport : '.$reportPath);

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}
