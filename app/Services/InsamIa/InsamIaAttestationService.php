<?php

namespace App\Services\InsamIa;

use App\Models\InsamIa\InsamIaAttempt;
use App\Models\InsamIa\InsamIaAttestation;
use App\Models\User;
use App\Services\TrainingProgressService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Délivrance des attestations de fin de parcours.
 *
 * Le QCM est corrigé par INSAM-IA et les vidéos sont hébergées par
 * InsamTechs, mais l'attestation est un document d'Estuaire : c'est nous qui
 * la délivrons, la référençons et en produisons le PDF. Elle reste donc
 * consultable même si les services tiers sont indisponibles.
 */
class InsamIaAttestationService
{
    public function __construct(private readonly TrainingProgressService $trainingProgress)
    {
    }

    /**
     * Délivre l'attestation d'une tentative réussie.
     *
     * Idempotent : une tentative déjà récompensée renvoie son attestation
     * existante plutôt que d'en créer une seconde.
     *
     * @throws InsamIaAttestationException si la tentative n'y donne pas droit
     */
    public function issueFor(InsamIaAttempt $attempt): InsamIaAttestation
    {
        if (!$attempt->isSubmitted()) {
            throw new InsamIaAttestationException(
                __('insam_ia.attestation_errors.not_submitted')
            );
        }

        if (!InsamIaAttestation::isEligible($attempt->percentage)) {
            throw new InsamIaAttestationException(
                __('insam_ia.attestation_errors.below_threshold', [
                    'threshold' => InsamIaAttestation::PASS_THRESHOLD,
                ])
            );
        }

        $existing = InsamIaAttestation::where('attempt_id', $attempt->id)->first();

        if ($existing) {
            // Le PDF a pu être purgé du stockage : on le régénère au besoin.
            return $this->ensurePdf($existing);
        }

        $attestation = InsamIaAttestation::create([
            'user_id' => $attempt->user_id,
            'attempt_id' => $attempt->id,
            // Posée explicitement : la valeur par défaut de la colonne ne
            // couvre pas les écritures où la source est significative.
            'source' => InsamIaAttestation::SOURCE_EVALUATION,
            'reference' => InsamIaAttestation::generateReference(),
            'title' => $this->courseTitle($attempt),
            'specialite' => $attempt->specialite,
            'score' => $attempt->score,
            'total' => $attempt->total,
            'percentage' => $attempt->percentage,
            'mention' => InsamIaAttestation::mentionFor($attempt->percentage),
            'issued_at' => now(),
        ]);

        return $this->ensurePdf($attestation);
    }

    /**
     * Délivre l'attestation d'une formation vidéo achevée.
     *
     * Le nombre de vidéos vient du catalogue InsamTechs : notre base ne
     * connaît que celles déjà ouvertes par l'étudiant, l'appelant doit donc
     * fournir le total attendu.
     *
     * Idempotent : une formation déjà attestée renvoie son attestation
     * existante plutôt que d'en créer une seconde.
     *
     * @throws InsamIaAttestationException si la formation n'est pas achevée
     */
    public function issueForTraining(
        User $user,
        int $formationId,
        string $formationTitle,
        int $videosTotal,
    ): InsamIaAttestation {
        if ($videosTotal <= 0) {
            throw InsamIaAttestationException::trainingWithoutVideos();
        }

        $existing = InsamIaAttestation::where('user_id', $user->id)
            ->where('formation_id', $formationId)
            ->first();

        if ($existing) {
            // Le PDF a pu être purgé du stockage : on le régénère au besoin.
            return $this->ensurePdf($existing);
        }

        $progress = $this->trainingProgress->formationProgress($user, $formationId, $videosTotal);

        if (!$progress['completed']) {
            throw InsamIaAttestationException::trainingIncomplete(
                $progress['videos_completed'],
                $progress['videos_total'],
            );
        }

        $attestation = InsamIaAttestation::create([
            'user_id' => $user->id,
            'source' => InsamIaAttestation::SOURCE_TRAINING,
            'formation_id' => $formationId,
            'videos_total' => $videosTotal,
            'reference' => InsamIaAttestation::generateReference(),
            'title' => $this->trainingTitle($formationTitle),
            // Le score est ici un décompte de vidéos vues, pas une note : la
            // formation étant achevée, le résultat est nécessairement de 100 %.
            'score' => $progress['videos_completed'],
            'total' => $videosTotal,
            'percentage' => 100,
            'mention' => InsamIaAttestation::mentionFor(100),
            'issued_at' => now(),
        ]);

        return $this->ensurePdf($attestation);
    }

    /**
     * Garantit la présence du PDF sur le disque public, en le générant si
     * nécessaire. Un échec de génération n'invalide pas l'attestation.
     */
    public function ensurePdf(InsamIaAttestation $attestation): InsamIaAttestation
    {
        if ($attestation->pdf_path && Storage::disk('public')->exists($attestation->pdf_path)) {
            return $attestation;
        }

        try {
            $path = sprintf(
                'attestations/%d/%s.pdf',
                $attestation->user_id,
                $attestation->reference
            );

            Storage::disk('public')->put($path, $this->render($attestation));

            $attestation->update(['pdf_path' => $path]);
        } catch (\Throwable $e) {
            Log::error('INSAM-IA : génération du PDF d\'attestation en échec', [
                'attestation_id' => $attestation->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $attestation->refresh();
    }

    /**
     * Rend le PDF en mémoire (téléchargement direct, pièce jointe).
     */
    public function render(InsamIaAttestation $attestation): string
    {
        $attestation->loadMissing('user');

        return Pdf::loadView('pdf.insam_ia_attestation', [
            'attestation' => $attestation,
            'holderName' => $this->holderName($attestation),
            'mentionLabel' => $attestation->mentionLabel(),
            'issuedAt' => $attestation->issued_at?->translatedFormat('d F Y'),
            'logo' => $this->logoDataUri(),
            'locale' => app()->getLocale(),
        ])
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'defaultFont' => 'DejaVu Sans',
                'dpi' => 96,
                'enable_php' => false,
                'enable_javascript' => false,
                // Le logo est injecté en data-URI : aucun accès distant requis.
                'enable_remote' => false,
            ])
            ->output();
    }

    private function courseTitle(InsamIaAttempt $attempt): string
    {
        $title = trim((string) ($attempt->session_title ?: $attempt->matiere));

        // Les intitulés INSAM-IA contiennent parfois des retours à la ligne.
        $title = preg_replace('/\s+/u', ' ', $title) ?: '';

        return $title !== ''
            ? $title
            : __('insam_ia.attestation.default_course');
    }

    /**
     * Intitulé de la formation, nettoyé comme celui des QCM : les titres du
     * catalogue InsamTechs contiennent eux aussi des retours à la ligne.
     */
    private function trainingTitle(string $formationTitle): string
    {
        $title = trim(preg_replace('/\s+/u', ' ', $formationTitle) ?: '');

        return $title !== ''
            ? $title
            : __('insam_ia.attestation.default_training');
    }

    private function holderName(InsamIaAttestation $attestation): string
    {
        $user = $attestation->user;

        if (!$user) {
            return __('insam_ia.attestation.unknown_holder');
        }

        $name = trim((string) $user->name);

        return $name !== '' ? $name : (string) $user->email;
    }

    /**
     * Logo encodé en data-URI : dompdf ne résout pas les URL distantes de
     * façon fiable, et le rendu doit fonctionner hors ligne.
     */
    private function logoDataUri(): ?string
    {
        $path = public_path('images/logo-estuaire-emploi.png');

        if (!is_file($path) || !is_readable($path)) {
            return null;
        }

        $contents = @file_get_contents($path);

        if ($contents === false) {
            return null;
        }

        return 'data:image/png;base64,' . base64_encode($contents);
    }
}
