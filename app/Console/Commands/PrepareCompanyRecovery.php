<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\User;
use App\Services\CompanyRecoveryService;
use Database\Seeders\EntreprisesCamerounSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PrepareCompanyRecovery extends Command
{
    protected $signature = 'companies:prepare-recovery {--apply : Enregistrer les entreprises éligibles}';
    protected $description = 'Rapproche les entreprises du fichier source avec leurs comptes précréés inutilisés (simulation par défaut)';

    public function handle(CompanyRecoveryService $service): int
    {
        $candidates = (new EntreprisesCamerounSeeder)->recoveryCandidates();
        if (!$candidates) { $this->error('Fichier source absent ou vide. Aucune entreprise modifiée.'); return self::FAILURE; }
        $eligible = 0;
        foreach ($candidates as $candidate) {
            DB::transaction(function () use ($candidate, $service, &$eligible) {
                $company = Company::where('email', $candidate['email'])->lockForUpdate()->first();
                if (!$company || $company->recovery_source || $company->recovery_claimed_at
                    || $company->name !== $candidate['name'] || $company->city !== $candidate['city']) return;
                $user = User::where('email', $candidate['email'])->lockForUpdate()->first();
                if (!$user || !$service->unusedAccount($company, $user)) return;
                $eligible++;
                if ($this->option('apply')) $company->forceFill([
                    'recovery_source' => 'entreprises_cameroun', 'recovery_source_key' => $candidate['key'], 'recovery_user_id' => $user->id,
                ])->save();
            });
        }
        $this->info($eligible.' entreprise(s) '.($this->option('apply') ? 'préparée(s).' : 'éligible(s). Simulation : aucune modification.'));
        return self::SUCCESS;
    }
}
