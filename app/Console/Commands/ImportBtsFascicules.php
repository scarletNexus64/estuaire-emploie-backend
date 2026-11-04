<?php

namespace App\Console\Commands;

use App\Models\Fascicule;
use App\Support\FasciculeCatalog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Importe la banque CNOENC du BTS (fichiers Word) dans les fascicules.
 *
 * Dossier attendu (archive « FASCICULE NUMERIQUE BTS » extraite) :
 *   …/BTS_Supports_de_Cours_CNOENC_Volume{1-4}/NN_COURS_BTS_{Filière}_{CODE}.docx
 *   …/BTS_Banque_Epreuves_CNOENC_Volume{1-4}/NN_BTS_{Filière}_{CODE}.docx
 *   …/Banques_corrigees_mise_a_jour/V{n}_NN_BTS_….docx   (remplace la banque du même code)
 * Les fichiers en vrac à la racine sont ignorés (doublons des volumes).
 *
 * Relancer la commande met à jour les fascicules déjà importés (même type + même filière).
 *
 *   php artisan fascicules:import-bts "/chemin/FASCICULE NUMERIQUE BTS" [--dry-run]
 */
class ImportBtsFascicules extends Command
{
    protected $signature = 'fascicules:import-bts
        {source : Dossier de l\'archive extraite}
        {--dry-run : Affiche le plan sans rien écrire}';

    protected $description = 'Importe les supports de cours et banques d\'épreuves BTS (CNOENC, .docx) dans les fascicules';

    private const AUTHOR = 'Banque CNOENC — BTS';
    private const STORAGE_DIR = 'fascicules/bts';

    public function handle(): int
    {
        $source = rtrim((string) $this->argument('source'), '/');
        if (!File::isDirectory($source)) {
            $this->error("Dossier introuvable : {$source}");

            return self::FAILURE;
        }

        $plan = $this->buildPlan($source);
        if ($plan === []) {
            $this->error('Aucun fichier de la banque CNOENC trouvé.');

            return self::FAILURE;
        }

        $this->table(
            ['Type', 'Code', 'Filière', 'Vol.', 'Fichier'],
            collect($plan)->map(fn ($item) => [
                $item['kind'] === FasciculeCatalog::KIND_SUPPORT ? 'Cours' : 'Banque',
                $item['code'],
                FasciculeCatalog::filiereName($item['code']),
                $item['volume'],
                basename($item['path']) . ($item['updated'] ? ' (mise à jour)' : ''),
            ])->all()
        );
        $this->info(count($plan) . ' fascicules à importer.');

        if ($this->option('dry-run')) {
            return self::SUCCESS;
        }

        $created = 0;
        $updated = 0;
        foreach ($plan as $item) {
            $this->importOne($item) ? $created++ : $updated++;
        }

        $this->info("Terminé : {$created} créés, {$updated} mis à jour.");

        return self::SUCCESS;
    }

    /**
     * @return list<array{kind:string,code:string,volume:int,path:string,updated:bool}>
     */
    private function buildPlan(string $source): array
    {
        $plan = [];
        $updates = [];

        foreach (File::allFiles($source) as $file) {
            if (strtolower($file->getExtension()) !== 'docx' || str_starts_with($file->getFilename(), '~$')) {
                continue;
            }
            $dir = basename($file->getPath());
            $code = FasciculeCatalog::codeFromBankFile($file->getFilename());
            if ($code === null) {
                if (preg_match('/CNOENC|Banques_corrigees/i', $dir)) {
                    $this->warn("Filière non reconnue, ignoré : {$dir}/{$file->getFilename()}");
                }
                continue;
            }

            if (preg_match('/Supports_de_Cours_CNOENC_Volume(\d)/i', $dir, $m)) {
                $kind = FasciculeCatalog::KIND_SUPPORT;
            } elseif (preg_match('/Banque_Epreuves_CNOENC_Volume(\d)/i', $dir, $m)) {
                $kind = FasciculeCatalog::KIND_BANQUE;
            } elseif (preg_match('/Banques_corrigees/i', $dir) && preg_match('/^V(\d)_/i', $file->getFilename(), $m)) {
                $updates[$code] = ['volume' => (int) $m[1], 'path' => $file->getPathname()];
                continue;
            } else {
                continue; // fichiers en vrac (racine) : doublons des volumes
            }

            $plan["{$kind}|{$code}"] = [
                'kind' => $kind,
                'code' => $code,
                'volume' => (int) $m[1],
                'path' => $file->getPathname(),
                'updated' => false,
            ];
        }

        // Les banques corrigées mises à jour remplacent la version du volume.
        foreach ($updates as $code => $update) {
            $plan[FasciculeCatalog::KIND_BANQUE . "|{$code}"] = [
                'kind' => FasciculeCatalog::KIND_BANQUE,
                'code' => $code,
                'volume' => $update['volume'],
                'path' => $update['path'],
                'updated' => true,
            ];
        }

        ksort($plan);

        return array_values($plan);
    }

    /** @return bool true si créé, false si mis à jour */
    private function importOne(array $item): bool
    {
        $kind = $item['kind'];
        $code = $item['code'];
        $isSupport = $kind === FasciculeCatalog::KIND_SUPPORT;

        $fascicule = Fascicule::withTrashed()->firstOrNew([
            'author' => self::AUTHOR,
            'kind' => $kind,
            'filiere_code' => $code,
        ]);
        $isNew = !$fascicule->exists;

        $slugCode = strtolower(str_replace(['/', ' '], '-', $code));
        $path = self::STORAGE_DIR . '/' . ($isSupport ? 'cours' : 'banques') . "/{$slugCode}.docx";
        Storage::disk(Fascicule::FILE_DISK)->put($path, File::get($item['path']));

        $name = FasciculeCatalog::filiereName($code);
        $fascicule->fill([
            'title' => ($isSupport ? 'Support de cours' : 'Banque d’épreuves corrigées') . ' — ' . $name,
            'description' => $isSupport
                ? "Cours condensé par épreuve du BTS {$name} : chapitres, exemples résolus, exercices et corrigés."
                : "Sujets types par épreuve du BTS {$name}, avec éléments de corrigé et barème.",
            'domain' => FasciculeCatalog::domainOf($code),
            'volume' => $item['volume'],
            'display_order' => $isSupport ? 0 : 1,
            'filiere' => $name,
            'level' => 'BTS',
            'file_path' => $path,
            'file_type' => Fascicule::TYPE_DOCX,
            'file_size' => File::size($item['path']),
            'is_published' => true,
        ]);
        $fascicule->deleted_at = null;
        $fascicule->save();

        return $isNew;
    }
}
