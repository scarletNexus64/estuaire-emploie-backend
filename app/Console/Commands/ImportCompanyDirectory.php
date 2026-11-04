<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Recruiter;
use App\Models\User;
use Database\Seeders\EntreprisesCamerounSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Throwable;

class ImportCompanyDirectory extends Command
{
    protected $signature = 'companies:import-directory {file} {--apply : Insérer les nouvelles entreprises (sinon simulation)}';
    protected $description = 'Import additif de l’annuaire Excel, avec simulation par défaut et rapport privé.';

    public static function normalized(string $text): string
    {
        return Str::slug(Str::ascii(trim($text)), '');
    }

    public function handle(): int
    {
        $root = storage_path('app/private/company-imports');
        File::ensureDirectoryExists($root, 0700);
        $lock = fopen($root.'/.lock', 'c');
        if (!$lock || !flock($lock, LOCK_EX | LOCK_NB)) {
            $this->error('Un import est déjà en cours.');
            return self::FAILURE;
        }
        try {
            $source = realpath($this->argument('file'));
            if (!$source || !is_readable($source)) {
                throw new \RuntimeException('Fichier source introuvable ou illisible.');
            }
            $reader = new EntreprisesCamerounSeeder;
            $rows = $reader->readRows($source);
            if (!$rows) {
                throw new \RuntimeException('Le fichier ne contient aucune entreprise.');
            }
            $mapping = require database_path('seeders/data/entreprises_import_categories.php');
            $categories = DB::table('company_categories')->orderBy('id')->get()->groupBy('level_1')->map(fn ($items) => $items->first()->id);
            $existing = DB::table('companies')->orderBy('id')->get();
            $emails = $existing->keyBy('email');
            $byName = $existing->groupBy(fn ($c) => self::normalized($c->city ?? '').'|'.self::normalized($c->name));
            $report = ['source_sha256' => hash_file('sha256', $source), 'apply' => (bool) $this->option('apply'), 'source_rows' => count($rows), 'existing_count' => count($existing), 'skipped' => [], 'inserted' => [], 'planned' => []];
            $seen = [];
            foreach ($rows as $index => $row) {
                if ($row['lat'] === null || $row['lng'] === null || !$row['place_id']) {
                    throw new \RuntimeException('Coordonnées ou identifiant Google absent à la ligne '.($index + 2));
                }
                $category = $mapping[$row['secteur']] ?? null;
                if (!$category || !isset($categories[$category])) {
                    throw new \RuntimeException('Catégorie non résolue : '.$row['secteur']);
                }
                // Identifiant indépendant de l'ordre des lignes et des emails reconstruits de l'ancien seeder.
                $email = 'directory.'.substr(hash('sha256', $row['place_id']), 0, 32).'@annuaire.estuaireemploi.invalid';
                $key = self::normalized($row['ville']).'|'.self::normalized($row['nom']);
                $matches = $byName->get($key, collect());
                $match = $emails->get($email);
                $reason = $match ? 'place_id' : null;
                if (!$match && $matches->isNotEmpty()) {
                    // Un même nom dans une ville peut désigner plusieurs agences : préférer le GPS.
                    $match = $matches->first(fn ($c) => $c->latitude !== null && $c->longitude !== null
                        && abs((float) $c->latitude - $row['lat']) < 0.001
                        && abs((float) $c->longitude - $row['lng']) < 0.001);
                    if ($match) $reason = 'name_city_gps';
                    elseif ($matches->count() === 1) { $match = $matches->first(); $reason = 'name_city_existing_preserved'; }
                }
                if ($match || isset($seen[$row['place_id']])) {
                    $report['skipped'][] = ['line' => $index + 2, 'name' => $row['nom'], 'city' => $row['ville'], 'existing_id' => $match?->id, 'reason' => $reason ?? 'duplicate_place_in_source'];
                    continue;
                }
                $seen[$row['place_id']] = true;
                $report['planned'][] = $row + ['email' => $email, 'category' => $category, 'category_id' => $categories[$category], 'source_line' => $index + 2];
            }
            $this->info(count($rows).' lignes ; '.count($report['planned']).' ajouts ; '.count($report['skipped']).' déjà présents/ignorés.');
            $cityCounts = array_count_values(array_column($report['planned'], 'ville'));
            $this->line(json_encode($cityCounts, JSON_UNESCAPED_UNICODE));
            if ($this->option('apply') && count($report['planned'])) {
                // Sauvegarde obligatoire avant toute écriture métier.
                if ($this->call('backup:database', ['--no-mail' => true]) !== self::SUCCESS) {
                    throw new \RuntimeException('Import annulé : sauvegarde préalable échouée.');
                }
                DB::transaction(function () use (&$report, $reader, $existing) {
                    $protected = [];
                    foreach (['users', 'recruiters', 'company_company_category'] as $table) {
                        $protected[$table] = DB::table($table)->orderBy('id')->get();
                    }
                    foreach ($report['planned'] as $row) {
                        if (User::withTrashed()->where('email', $row['email'])->exists()) {
                            throw new \RuntimeException('Email technique déjà utilisé, import annulé : '.$row['nom']);
                        }
                        $company = Company::create([
                            'name' => $row['nom'], 'email' => $row['email'], 'phone' => $reader->normalizePhone($row['tel']),
                            'description' => $reader->buildDescription($row), 'domain' => $row['category'], 'sector' => $row['secteur'],
                            'website' => $row['website'], 'address' => $reader->buildAddress($row), 'city' => $row['ville'], 'country' => 'Cameroun',
                            'latitude' => $row['lat'], 'longitude' => $row['lng'], 'status' => 'verified', 'subscription_plan' => 'free', 'verified_at' => now(),
                        ]);
                        $company->categories()->attach($row['category_id']);
                        $user = User::create([
                            'name' => $row['nom'], 'email' => $row['email'], 'phone' => $reader->normalizePhone($row['tel']),
                            'role' => 'recruiter', 'password' => Hash::make(Str::password(32)), 'must_change_password' => true,
                            'is_active' => true, 'country' => 'CM', 'locale' => 'fr', 'preferred_currency' => 'XAF',
                            'current_company_id' => $company->id, 'email_verified_at' => null,
                        ]);
                        $recruiter = Recruiter::create(['user_id' => $user->id, 'company_id' => $company->id, 'position' => 'Responsable RH', 'can_publish' => true, 'can_view_applications' => true, 'can_modify_company' => true]);
                        $report['inserted'][] = ['company_id' => $company->id, 'user_id' => $user->id, 'recruiter_id' => $recruiter->id, 'source_line' => $row['source_line'], 'place_id' => $row['place_id']];
                        if (count($report['inserted']) % 100 === 0) $this->line(count($report['inserted']).' entreprises préparées dans la transaction…');
                    }
                    $after = DB::table('companies')->whereIn('id', $existing->pluck('id'))->orderBy('id')->get();
                    if (json_encode($existing) !== json_encode($after)) {
                        throw new \RuntimeException('Une fiche préexistante a changé durant l’import : annulation de nos insertions.');
                    }
                    foreach ($protected as $table => $before) {
                        $afterRows = DB::table($table)->whereIn('id', $before->pluck('id'))->orderBy('id')->get();
                        if (json_encode($before) !== json_encode($afterRows)) {
                            throw new \RuntimeException('Données existantes modifiées dans '.$table.' : annulation.');
                        }
                    }
                    $report['existing_companies_unchanged'] = true;
                    $report['existing_users_recruiters_categories_unchanged'] = true;
                });
            }
            $path = $root.'/'.now()->format('Ymd-His').'-'.($this->option('apply') ? 'applied' : 'dry-run').'-'.bin2hex(random_bytes(3)).'.json';
            file_put_contents($path, json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
            chmod($path, 0600);
            $this->info('Rapport : '.$path);
            $this->info($this->option('apply') ? count($report['inserted']).' entreprises insérées ; fiches existantes préservées.' : 'Simulation terminée : aucune écriture en base.');
            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }
}
