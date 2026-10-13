<?php

namespace App\Console\Commands;

use App\Services\InsamtechsCatalogService;
use Illuminate\Console\Command;

/**
 * Resynchronise la copie locale du catalogue InsamTechs utilisée par la
 * recherche des formations vidéo.
 */
class SyncInsamtechsCatalog extends Command
{
    protected $signature = 'insamtechs:sync-catalog';

    protected $description = 'Synchronise le catalogue des formations vidéo InsamTechs (recherche)';

    public function handle(InsamtechsCatalogService $catalog): int
    {
        $count = $catalog->sync();

        if ($count === null) {
            $this->error('Synchronisation échouée : le catalogue précédent est conservé.');

            return self::FAILURE;
        }

        $this->info("{$count} formations synchronisées.");

        return self::SUCCESS;
    }
}
