<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Classement des fascicules et annales, calqué sur la banque CNOENC du BTS :
 * Domaine (≈ volume CNOENC) → Filière → contenus.
 *
 * - Les fascicules (supports de cours, banques d'épreuves) portent `domain` et `filiere_code`.
 * - Les annales (exam_papers) portent `filiere` (un code de ce catalogue) ; leur domaine se
 *   déduit de la filière.
 */
class FasciculeCatalog
{
    public const KIND_SUPPORT = 'support_cours';
    public const KIND_BANQUE = 'banque_epreuves';
    public const KIND_OTHER = 'autre';

    /** Dossier des fascicules sans filière connue. */
    public const DOMAIN_OTHER = 'autres';

    /**
     * Domaines, dans l'ordre d'affichage.
     * `volume` = numéro du volume CNOENC correspondant (null si hors banque).
     */
    public const DOMAINS = [
        'agri_numerique' => ['label' => 'Agriculture, Eau, Environnement & Numérique', 'volume' => 1, 'icon' => 'eco'],
        'industriel' => ['label' => 'Industriel & Technologique', 'volume' => 2, 'icon' => 'factory'],
        'gestion' => ['label' => 'Commercial, Gestion & Droit', 'volume' => 3, 'icon' => 'business'],
        'services' => ['label' => 'Services, Santé, Arts & Communication', 'volume' => 4, 'icon' => 'health'],
        'tronc_commun' => ['label' => 'Tronc commun & Transversales', 'volume' => null, 'icon' => 'layers'],
        'hnd' => ['label' => 'HND (Higher National Diploma)', 'volume' => null, 'icon' => 'school'],
        self::DOMAIN_OTHER => ['label' => 'Autres fascicules', 'volume' => null, 'icon' => 'folder'],
    ];

    /**
     * Filières : code => [nom, domaine].
     * L'ordre interne n'importe pas : l'affichage trie par nom.
     */
    public const FILIERES = [
        // ── Volume 1 : Agriculture, Eau, Environnement & Numérique ──
        'PA' => ['Production Animale', 'agri_numerique'],
        'PV' => ['Production Végétale', 'agri_numerique'],
        'AQC' => ['Aquaculture', 'agri_numerique'],
        'CAP' => ['Conseil Agropastoral', 'agri_numerique'],
        'EA' => ['Entrepreneuriat Agropastoral', 'agri_numerique'],
        'CBR' => ['Construction des Bâtiments et Routes Rurales', 'agri_numerique'],
        'AEP' => ['Eau Potable et Aménagement Hydro-agricole', 'agri_numerique'],
        'HYT' => ['Hydraulique, Traitement des Eaux et Assainissement', 'agri_numerique'],
        'GF' => ['Gestion Forestière', 'agri_numerique'],
        'GEN' => ['Gestion Environnementale', 'agri_numerique'],
        'GL' => ['Génie Logiciel', 'agri_numerique'],
        'IWD' => ['Infographie et Web Design', 'agri_numerique'],
        'MSI' => ['Maintenance des Systèmes Informatiques', 'agri_numerique'],
        'IIA' => ['Informatique Industrielle et Automatique', 'agri_numerique'],
        'ECM' => ['E-commerce et Marketing Numérique', 'agri_numerique'],
        'TEL' => ['Télécommunications', 'agri_numerique'],
        'RS' => ['Réseaux et Sécurité', 'agri_numerique'],
        'T-AGRI' => ['Épreuves transversales — Agriculture, Élevage et Métiers de l’Eau', 'agri_numerique'],
        'T-EAU' => ['Épreuves transversales — Eau Potable (AEP / HYT)', 'agri_numerique'],
        'T-GI' => ['Épreuves transversales — Maths et Physique (Génie Informatique)', 'agri_numerique'],

        // ── Volume 2 : Industriel & Technologique ──
        'ET' => ['Électrotechnique', 'industriel'],
        'MSE' => ['Maintenance des Systèmes Électroniques', 'industriel'],
        'ENR' => ['Énergies Renouvelables', 'industriel'],
        'CIR' => ['Contrôle, Instrumentation et Régulation', 'industriel'],
        'MEI' => ['Maintenance des Équipements Industriels', 'industriel'],
        'MAB' => ['Maintenance des Appareils Biomédicaux', 'industriel'],
        'CH' => ['Chimie Générale', 'industriel'],
        'GCP' => ['Génie Chimique et des Procédés', 'industriel'],
        'T-CHIM' => ['Épreuves transversales — Chimie et Génie Chimique', 'industriel'],
        'BA' => ['Bâtiment', 'industriel'],
        'TP' => ['Travaux Publics', 'industriel'],
        'GEG' => ['Géotechnique et Géologie Appliquée', 'industriel'],
        'GT' => ['Géomètre Topographe', 'industriel'],
        'IS' => ['Installation Sanitaire', 'industriel'],
        'MB' => ['Menuiserie et Ébénisterie', 'industriel'],
        'CM' => ['Construction Mécanique', 'industriel'],
        'FM' => ['Fabrication Mécanique', 'industriel'],
        'T-MECA' => ['Épreuves transversales — Construction et Fabrication Mécanique', 'industriel'],
        'CME' => ['Construction Métallique', 'industriel'],
        'CHS' => ['Chaudronnerie et Soudure', 'industriel'],
        'MAVA' => ['Maintenance Après-Vente Automobile', 'industriel'],
        'MECA' => ['Mécatronique', 'industriel'],
        'MIP' => ['Maintenance Industrielle et Productique', 'industriel'],
        'MN' => ['Marine Marchande — Mécanique Navale', 'industriel'],
        'TMM-GA' => ['Marine Marchande — Gestion et Administration', 'industriel'],
        'TMM-HSE' => ['Marine Marchande — Hygiène, Sécurité et Environnement', 'industriel'],
        'FC' => ['Froid et Climatisation', 'industriel'],
        'ABB' => ['Analyses Biologiques et Biochimiques', 'industriel'],
        'DIE' => ['Diététique', 'industriel'],
        'IAL' => ['Industrie Alimentaire', 'industriel'],
        'PHA' => ['Phytothérapie et Aromathérapie', 'industriel'],
        'BTA' => ['Biotechnologie Agricole', 'industriel'],
        'T-ALIM' => ['Épreuves transversales — ABB, IAL et Diététique', 'industriel'],
        'INM' => ['Ingénierie Minière', 'industriel'],
        'INP' => ['Ingénierie Pétrolière', 'industriel'],
        'MGA' => ['Mines et Géologie Appliquée', 'industriel'],
        'T-MINES' => ['Épreuves transversales — Mines et Pétrole', 'industriel'],
        'T-IND' => ['Épreuves transversales — Domaine Industriel et Technologique', 'industriel'],
        'GMH-IND' => ['Industrie — GMH', 'industriel'],

        // ── Volume 3 : Commercial, Gestion & Droit ──
        'CI' => ['Commerce International', 'gestion'],
        'MCV' => ['Marketing, Commerce et Vente', 'gestion'],
        'GCT-CF' => ['Collectivités Territoriales — Comptabilité et Finance', 'gestion'],
        'GCT-FC' => ['Collectivités Territoriales — Fiscalité', 'gestion'],
        'GCT-AC' => ['Collectivités Territoriales — Administration', 'gestion'],
        'AS' => ['Assurance', 'gestion'],
        'GLT' => ['Gestion Logistique et Transport', 'gestion'],
        'GQ' => ['Gestion de la Qualité', 'gestion'],
        'MEV' => ['Management Événementiel', 'gestion'],
        'GONG' => ['Gestion des ONG', 'gestion'],
        'GPR' => ['Gestion des Projets', 'gestion'],
        'GSI' => ['Gestion des Systèmes d’Information', 'gestion'],
        'AMA' => ['Assistant Manager', 'gestion'],
        'GRH' => ['Gestion des Ressources Humaines', 'gestion'],
        'BAF' => ['Banque et Finance', 'gestion'],
        'MAS' => ['Management du Sport', 'gestion'],
        'CGE' => ['Comptabilité et Gestion des Entreprises', 'gestion'],
        'STA' => ['Statistiques', 'gestion'],
        'PIM' => ['Professions Immobilières', 'gestion'],
        'T-GEST' => ['Épreuves transversales — Filière Gestion', 'gestion'],
        'DA' => ['Droit des Affaires et de l’Entreprise', 'gestion'],
        'DFD' => ['Droit Foncier et Domanial', 'gestion'],
        'AJ' => ['Assistanat Judiciaire', 'gestion'],
        'GFI' => ['Gestion Fiscale', 'gestion'],
        'DOT' => ['Douane et Transit', 'gestion'],
        'ACT' => ['ACT', 'gestion'],
        'CFP' => ['CFP', 'gestion'],

        // ── Volume 4 : Services, Santé, Arts & Communication ──
        'EPS' => ['Éducation Physique et Sportive', 'services'],
        'GMH' => ['Hôtellerie — Gestion et Management Hôtelier', 'services'],
        'MTH' => ['Hôtellerie — Management et Techniques d’Hébergement', 'services'],
        'GCU' => ['Restauration — Génie Culinaire', 'services'],
        'CSR' => ['Restauration — Commercialisation et Services', 'services'],
        'MT' => ['Tourisme et Loisirs — Management Touristique', 'services'],
        'T-THR' => ['Épreuves transversales — Tourisme, Hôtellerie et Restauration', 'services'],
        'PG' => ['Puériculture et Gérontologie', 'services'],
        'DEP' => ['Design Produit', 'services'],
        'DM' => ['Design de Mode', 'services'],
        'DI' => ['Design d’Intérieur', 'services'],
        'T-DESIGN' => ['Épreuves transversales — Design de Mode et d’Intérieur', 'services'],
        'DES' => ['Dessin Animé', 'services'],
        'IH' => ['Industrie de l’Habillement', 'services'],
        'PAV' => ['Photographie et Audiovisuel', 'services'],
        'JO' => ['Journalisme', 'services'],
        'CO' => ['Communication des Organisations', 'services'],
        'T-JOCO' => ['Épreuves transversales — Journalisme et Communication', 'services'],
        'ADS' => ['Administration Scolaire', 'services'],
        'SF' => ['Sage-Femme', 'services'],
        'SI' => ['Sciences Infirmières', 'services'],
        'T-SISF' => ['Épreuves transversales — Sciences Infirmières et Sage-Femme', 'services'],
        'KT' => ['Kinésithérapie', 'services'],
        'ODO' => ['Odontostomatologie', 'services'],
        'OPL' => ['Optique Lunetterie', 'services'],
        'TL' => ['Techniques de Laboratoire', 'services'],
        'RIM' => ['Radiologie et Imagerie Médicale', 'services'],
        'TPH' => ['Techniques Pharmaceutiques', 'services'],
        'T-SANTE' => ['Épreuves transversales — Domaine Santé', 'services'],

        // ── Hors volumes ──
        'TC' => ['Tronc commun — Toutes spécialités', 'tronc_commun'],
        'HND' => ['HND — Toutes options', 'hnd'],
    ];

    /**
     * Fichiers de la banque CNOENC sans code en fin de nom → code du catalogue.
     * Clé : nom de fichier normalisé (voir normalizeStem()).
     */
    private const STEM_CODES = [
        'TRANSVERSALES_AGRICULTURE-ELEVAGE_METIER_EAU' => 'T-AGRI',
        'TRANSVERSALE_AEP-HYT_EAU_POTABLE' => 'T-EAU',
        'TRANSVERSALES_MATHS_PHYSIQUE_GI' => 'T-GI',
        'TRANSVERSALES_GENERALES_TOUTES_SPECIALITES' => 'TC',
        'EPREUVE_TRANSVERSALE_CHIMIE_GENIE_CHIMIQUE_DES_PROCEDES_CH_GCP' => 'T-CHIM',
        'EPREUVES_TRANSVERSALES_CM_FM' => 'T-MECA',
        'EPREUVES_TRANSVERSALES_ABB_IAL_DIE' => 'T-ALIM',
        'EPREUVES_TRANSVERSALES_INM_INP_MGA' => 'T-MINES',
        'EPREUVES_TRANSVERSALES_DU_DOMAINE_INDUSTRIEL_ET_TECHNOLOGIE' => 'T-IND',
        'TECHNOLOGIES_DE_LA_MARINE_MARCHANDE_OPTION_GESTION_ET_ADMINISTRATION_D' => 'TMM-GA',
        'TECHNOLOGIES_DE_LA_MARINE_MARCHANDE_OPTION_HYGIENE_SECURITE_ENVIRONNEM' => 'TMM-HSE',
        'GESTION_DES_COLLECTIVITES_TERRITORIALES_OPTION_COMPTABILITE_ET_FINANCE' => 'GCT-CF',
        'GESTION_DES_COLLECTIVITES_TERRITORIALES_OPTION_FISCALITE_DES_COLLECTIV' => 'GCT-FC',
        'GESTION_DES_COLLECTIVITES_TERRITORIALES_OPTION_ADMINISTRATION_DES_COLL' => 'GCT-AC',
        'EPREUVES_TRANSVERSALES_FILIERE_GESTION' => 'T-GEST',
        'EPREUVES_TRANSVERSALES_TOURISME_HOTELLERIE_ET_RESTAURATION' => 'T-THR',
        'EPREUVE_S_TRANSVERSALE_S_DESIGN_DE_MODE_DESIGN_DINTERIEUR' => 'T-DESIGN',
        'EPREUVES_TRANSVERSALES_JOURNALISME_COMMUNICATION_DES_ORGANISATIONS' => 'T-JOCO',
        'EPREUVE_TRANSVERSALE_SCIENCES_INFIRMIERES_SAGE_FEMME' => 'T-SISF',
        'EPREUVES_TRANSVERSALES_DOMAINE_SANTE' => 'T-SANTE',
    ];

    /**
     * Code de filière des annales (dossier source des épreuves) → code du catalogue.
     * Les codes identiques (GL, MCV…) n'ont pas besoin d'y figurer.
     */
    private const EXAM_ALIASES = [
        'RESEAUX' => 'RS',
        'TELECOMMUNICATIONS' => 'TEL',
        'BF' => 'BAF',
        'ASS' => 'AS',
        'HND-SWE' => 'HND',
        'GENERAL' => 'TC',
        // Les annales « Industrie/GMH » ne sont pas la filière hôtelière du CNOENC.
        'GMH' => 'GMH-IND',
    ];

    public static function domainLabel(string $domain): string
    {
        return self::DOMAINS[$domain]['label'] ?? Str::title(str_replace('_', ' ', $domain));
    }

    public static function hasDomain(?string $domain): bool
    {
        return $domain !== null && isset(self::DOMAINS[$domain]);
    }

    public static function hasFiliere(?string $code): bool
    {
        return $code !== null && isset(self::FILIERES[$code]);
    }

    public static function filiereName(string $code): string
    {
        return self::FILIERES[$code][0] ?? $code;
    }

    /** Domaine d'une filière (DOMAIN_OTHER si inconnue). */
    public static function domainOf(?string $code): string
    {
        return ($code !== null ? (self::FILIERES[$code][1] ?? null) : null) ?? self::DOMAIN_OTHER;
    }

    /** Codes des filières d'un domaine. */
    public static function filieresOf(string $domain): array
    {
        return array_keys(array_filter(self::FILIERES, fn ($f) => $f[1] === $domain));
    }

    /** Libellé affiché : « Génie Logiciel (GL) », sans code pour les transversales. */
    public static function filiereLabel(string $code): string
    {
        $name = self::filiereName($code);
        $isSynthetic = str_starts_with($code, 'T-') || str_contains($code, '-') || in_array($code, ['TC', 'HND', 'ACT', 'CFP'], true);

        return $isSynthetic ? $name : "{$name} ({$code})";
    }

    /** Nom de fichier CNOENC → forme comparable (sans numéro, préfixe ni accents). */
    public static function normalizeStem(string $fileName): string
    {
        $stem = pathinfo($fileName, PATHINFO_FILENAME);
        $stem = preg_replace('/^(V\d+_)?\d+_(COURS_)?BTS_/i', '', $stem);

        return strtoupper(Str::ascii($stem));
    }

    /** Fichier de la banque CNOENC → code de filière (null si non reconnu). */
    public static function codeFromBankFile(string $fileName): ?string
    {
        $stem = self::normalizeStem($fileName);
        if (isset(self::STEM_CODES[$stem])) {
            return self::STEM_CODES[$stem];
        }
        $last = strtoupper((string) Str::afterLast($stem, '_'));

        return self::hasFiliere($last) ? $last : null;
    }

    /**
     * Code de filière des annales (nom du dossier, ex. « Réseaux », ou son slug
     * « reseaux ») → code du catalogue (null si inconnu).
     */
    public static function codeFromExamFiliere(?string $raw): ?string
    {
        if ($raw === null || trim($raw) === '') {
            return null;
        }
        $key = strtoupper(Str::ascii(trim($raw)));
        $key = str_replace([' ', '_'], '-', $key);
        $code = self::EXAM_ALIASES[$key] ?? $key;

        return self::hasFiliere($code) ? $code : null;
    }
}
