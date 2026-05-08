<?php

namespace App\Console\Commands;

use App\Models\Job;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SpreadAndFeatureThemes extends Command
{
    protected $signature = 'themes:spread-and-feature
                            {--start=2025-08-01 : Date de début pour la répartition}
                            {--end=2026-04-15 : Date de fin pour la répartition}
                            {--include-old : Inclure aussi les thèmes IT du 2026-04-21}
                            {--dry-run : Simule sans modifier}';

    protected $description = 'Répartit les dates des thèmes Estuaire Emploi et met en avant 30 jobs variés';

    private array $featurePatterns = [
        ['cat' => 1,  'pat' => 'GÉNIE LOGICIEL',                              'count' => 4],
        ['cat' => 1,  'pat' => 'TÉLÉCOMMUNICATIONS',                          'count' => 4],
        ['cat' => 1,  'pat' => 'INFOGRAPHIE & WEB DESIGN',                    'count' => 2],
        ['cat' => 1,  'pat' => 'INFORMATIQUE INDUSTRIELLE & AUTOMATISME',     'count' => 2],
        ['cat' => 1,  'pat' => 'MAINTENANCE DES SYSTÈMES INFORMATIQUES',      'count' => 2],
        ['cat' => 1,  'pat' => 'COMMERCE & MARKETING NUMÉRIQUE',              'count' => 2],
        ['cat' => 3,  'pat' => 'BTS GESTION DES RESSOURCES HUMAINES',         'count' => 1],
        ['cat' => 10, 'pat' => 'BTS TRANSPORT-LOGISTIQUE',                    'count' => 1],
        ['cat' => 17, 'pat' => 'BTS COMPTABILITÉ',                            'count' => 1],
        ['cat' => 18, 'pat' => 'BTS BANQUE',                                  'count' => 2],
        ['cat' => 19, 'pat' => 'BTS COMMERCE INTERNATIONAL',                  'count' => 1],
        ['cat' => 20, 'pat' => 'BTS MARKETING',                               'count' => 1],
        ['cat' => 21, 'pat' => 'BTS ASSISTANT MANAGER',                       'count' => 1],
        ['cat' => 22, 'pat' => 'BTS TOURISME',                                'count' => 1],
        ['cat' => 23, 'pat' => 'BTS COMMUNICATION',                           'count' => 1],
        ['cat' => 24, 'pat' => 'BTS GESTION DES COLLECTIVITÉS TERRITORIALES', 'count' => 1],
        ['cat' => 25, 'pat' => 'BTS GESTION DE LA QUALITÉ',                   'count' => 1],
        ['cat' => 26, 'pat' => 'BTS MAINTENANCE INDUSTRIELLE',                'count' => 1],
        ['cat' => 27, 'pat' => 'BTS ÉLECTROTECHNIQUE',                        'count' => 1],
        ['cat' => 28, 'pat' => 'BTS ÉNERGIES RENOUVELABLES',                  'count' => 1],
    ];

    public function handle(): int
    {
        $start = Carbon::parse($this->option('start'))->startOfDay();
        $end = Carbon::parse($this->option('end'))->endOfDay();
        $dryRun = (bool) $this->option('dry-run');
        $includeOld = (bool) $this->option('include-old');

        $query = Job::where('company_id', 39)
            ->where('title', 'like', 'Thème%')
            ->orderBy('id');

        if (!$includeOld) {
            $query->whereDate('created_at', '2026-04-30');
        }

        $jobs = $query->get();
        $total = $jobs->count();

        if ($total === 0) {
            $this->warn('Aucun job à traiter');
            return self::SUCCESS;
        }

        $this->info("=== Étape 1 : Répartition des dates ===");
        $this->info("Jobs : $total");
        $this->info("Plage : {$start->format('Y-m-d')} → {$end->format('Y-m-d')}");
        if ($dryRun) $this->warn('DRY-RUN');

        $diffSeconds = $start->diffInSeconds($end);
        $stepSeconds = (int) ($diffSeconds / max($total - 1, 1));

        $shuffled = $jobs->shuffle()->values();

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($shuffled as $i => $job) {
            $newDate = $start->copy()->addSeconds($stepSeconds * $i);
            if (!$dryRun) {
                DB::table('jobs')->where('id', $job->id)->update([
                    'created_at' => $newDate,
                    'updated_at' => $newDate,
                    'published_at' => $newDate,
                ]);
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();

        $this->info("\n=== Étape 2 : Mise en avant (is_featured) ===");

        if (!$dryRun) {
            Job::where('company_id', 39)
                ->where('title', 'like', 'Thème%')
                ->update(['is_featured' => false]);
        }

        $featured = [];
        foreach ($this->featurePatterns as $p) {
            $picks = Job::where('company_id', 39)
                ->where('category_id', $p['cat'])
                ->where('title', 'like', 'Thème%')
                ->where('title', 'like', '%' . $p['pat'] . '%')
                ->inRandomOrder()
                ->take($p['count'])
                ->get();

            foreach ($picks as $j) {
                if (!$dryRun) {
                    DB::table('jobs')->where('id', $j->id)->update([
                        'is_featured' => true,
                    ]);
                }
                $featured[] = "  cat={$p['cat']} | id={$j->id} | " . substr($j->title, 0, 90);
            }
        }

        $this->info("Total featured : " . count($featured));
        foreach ($featured as $line) {
            $this->line($line);
        }

        return self::SUCCESS;
    }
}
