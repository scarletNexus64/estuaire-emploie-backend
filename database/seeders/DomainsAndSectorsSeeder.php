<?php

namespace Database\Seeders;

use App\Models\Domain;
use App\Models\Sector;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Creates the `domains` and `sectors` reference data from
 * config/domains_sectors.php (the legacy hardcoded config).
 *
 * Idempotent : safe to re-run (uses updateOrCreate).
 *
 * NOTE: Translations are not populated here — run TranslationsSeeder
 * afterwards (or rely on the DatabaseSeeder ordering).
 */
class DomainsAndSectorsSeeder extends Seeder
{
    public function run(): void
    {
        $config = config('domains_sectors', []);

        if (empty($config)) {
            $this->command?->warn('[DomainsAndSectorsSeeder] config/domains_sectors.php is empty — nothing to seed.');

            return;
        }

        $order = 0;
        $domainsCreated = 0;
        $sectorsCreated = 0;

        foreach ($config as $domainName => $sectorNames) {
            $order++;
            $domain = Domain::updateOrCreate(
                ['slug' => Str::slug($domainName)],
                [
                    'name' => $domainName,
                    'display_order' => $order,
                    'is_active' => true,
                ]
            );
            $domainsCreated++;

            $sectorOrder = 0;
            foreach ((array) $sectorNames as $sectorName) {
                $sectorOrder++;
                Sector::updateOrCreate(
                    [
                        'domain_id' => $domain->id,
                        'slug' => Str::slug($sectorName),
                    ],
                    [
                        'name' => $sectorName,
                        'display_order' => $sectorOrder,
                        'is_active' => true,
                    ]
                );
                $sectorsCreated++;
            }
        }

        $this->command?->info("[DomainsAndSectorsSeeder] {$domainsCreated} domains, {$sectorsCreated} sectors seeded.");
    }
}
