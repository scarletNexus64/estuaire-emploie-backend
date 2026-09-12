<?php

namespace App\Services\Payment;

/**
 * Catalogue KPay — source unique de vérité pour les pays, opérateurs,
 * devises et préfixes téléphoniques.
 *
 * Aligné sur le catalogue officiel KPay (12 pays / 23 codes provider).
 * Toute autre liste (seeder `countries`, dérivation d'opérateur, mapping
 * de devise) doit s'appuyer sur cette classe plutôt que de redéfinir la
 * sienne : c'est précisément la divergence entre trois listes distinctes
 * qui faisait échouer les paiements hors Cameroun.
 *
 * @see kpay-context-php copy.md — « Pays couverts et catalogue des providers »
 */
class KPayCatalog
{
    /**
     * Pays supportés, indexés par code ISO 3166-1 alpha-3 (celui que KPay
     * emploie dans ses réponses `country`).
     *
     * - `dial`     : indicatif international sans « + »
     * - `iso2`     : code alpha-2 (utilisé par la table `countries` et les drapeaux)
     * - `currency` : devise de référence des transactions du pays
     * - `decimals` : true si le provider accepte des fractions
     * - `providers`: codes opérateurs exacts attendus par le champ `provider`
     *
     * Les préfixes `prefixes` servent à deviner l'opérateur depuis un numéro
     * (complément local du endpoint KPay `predict-provider`).
     */
    public const COUNTRIES = [
        'BEN' => [
            'iso2' => 'BJ',
            'dial' => '229',
            'name' => 'Bénin',
            'currency' => 'XOF',
            'decimals' => false,
            'providers' => [
                'MTN_MOMO_BEN' => ['label' => 'MTN MoMo', 'prefixes' => ['51', '52', '53', '54', '61', '62', '66', '67', '69', '90', '91', '96', '97']],
                'MOOV_BEN' => ['label' => 'Moov Money', 'prefixes' => ['55', '56', '57', '58', '59', '60', '63', '64', '65', '68', '94', '95', '98', '99']],
            ],
        ],
        'CMR' => [
            'iso2' => 'CM',
            'dial' => '237',
            'name' => 'Cameroun',
            'currency' => 'XAF',
            'decimals' => false,
            'providers' => [
                'MTN_MOMO_CMR' => ['label' => 'MTN MoMo', 'prefixes' => ['67', '68', '650', '651', '652', '653', '654']],
                'ORANGE_CMR' => ['label' => 'Orange Money', 'prefixes' => ['69', '655', '656', '657', '658', '659']],
            ],
        ],
        'CIV' => [
            'iso2' => 'CI',
            'dial' => '225',
            'name' => "Côte d'Ivoire",
            'currency' => 'XOF',
            'decimals' => false,
            'providers' => [
                'MTN_MOMO_CIV' => ['label' => 'MTN MoMo', 'prefixes' => ['04', '05', '06', '44', '45', '46', '54', '55', '56', '64', '65', '66']],
                'ORANGE_CIV' => ['label' => 'Orange Money', 'prefixes' => ['07', '08', '09', '47', '48', '49', '57', '58', '59', '67', '68', '69']],
            ],
        ],
        'COD' => [
            'iso2' => 'CD',
            'dial' => '243',
            'name' => 'RD Congo',
            'currency' => 'CDF',
            'decimals' => true,
            'providers' => [
                'VODACOM_MPESA_COD' => ['label' => 'Vodacom M-Pesa', 'prefixes' => ['81', '82', '83']],
                'AIRTEL_COD' => ['label' => 'Airtel Money', 'prefixes' => ['99', '97']],
                'ORANGE_COD' => ['label' => 'Orange Money', 'prefixes' => ['84', '85', '89', '80']],
            ],
        ],
        'GAB' => [
            'iso2' => 'GA',
            'dial' => '241',
            'name' => 'Gabon',
            'currency' => 'XAF',
            'decimals' => true,
            'providers' => [
                'AIRTEL_GAB' => ['label' => 'Airtel Money', 'prefixes' => ['74', '77', '76', '66', '65', '62', '60']],
            ],
        ],
        'KEN' => [
            'iso2' => 'KE',
            'dial' => '254',
            'name' => 'Kenya',
            'currency' => 'KES',
            'decimals' => true,
            'providers' => [
                'MPESA_KEN' => ['label' => 'M-Pesa', 'prefixes' => ['70', '71', '72', '74', '79', '11']],
            ],
        ],
        'COG' => [
            'iso2' => 'CG',
            'dial' => '242',
            'name' => 'Congo',
            'currency' => 'XAF',
            'decimals' => false,
            'providers' => [
                'AIRTEL_COG' => ['label' => 'Airtel Money', 'prefixes' => ['04', '05', '06']],
                'MTN_MOMO_COG' => ['label' => 'MTN MoMo', 'prefixes' => ['01', '02', '03']],
            ],
        ],
        'RWA' => [
            'iso2' => 'RW',
            'dial' => '250',
            'name' => 'Rwanda',
            'currency' => 'RWF',
            'decimals' => false,
            'providers' => [
                'AIRTEL_RWA' => ['label' => 'Airtel Money', 'prefixes' => ['72', '73']],
                'MTN_MOMO_RWA' => ['label' => 'MTN MoMo', 'prefixes' => ['78', '79']],
            ],
        ],
        'SEN' => [
            'iso2' => 'SN',
            'dial' => '221',
            'name' => 'Sénégal',
            'currency' => 'XOF',
            'decimals' => false,
            'providers' => [
                'ORANGE_SEN' => ['label' => 'Orange Money', 'prefixes' => ['77', '78']],
                'FREE_SEN' => ['label' => 'Free Money', 'prefixes' => ['76']],
            ],
        ],
        'SLE' => [
            'iso2' => 'SL',
            'dial' => '232',
            'name' => 'Sierra Leone',
            'currency' => 'SLE',
            'decimals' => true,
            'providers' => [
                'ORANGE_SLE' => ['label' => 'Orange Money', 'prefixes' => ['76', '75', '78', '79']],
            ],
        ],
        'UGA' => [
            'iso2' => 'UG',
            'dial' => '256',
            'name' => 'Ouganda',
            'currency' => 'UGX',
            'decimals' => true,
            'providers' => [
                'AIRTEL_OAPI_UGA' => ['label' => 'Airtel Money', 'prefixes' => ['70', '74', '75']],
                'MTN_MOMO_UGA' => ['label' => 'MTN MoMo', 'prefixes' => ['77', '78', '76', '39']],
            ],
        ],
        'ZMB' => [
            'iso2' => 'ZM',
            'dial' => '260',
            'name' => 'Zambie',
            'currency' => 'ZMW',
            'decimals' => true,
            'providers' => [
                'AIRTEL_OAPI_ZMB' => ['label' => 'Airtel Money', 'prefixes' => ['97', '77']],
                'MTN_MOMO_ZMB' => ['label' => 'MTN MoMo', 'prefixes' => ['96', '76']],
                'ZAMTEL_ZMB' => ['label' => 'Zamtel Money', 'prefixes' => ['95', '75']],
            ],
        ],
    ];

    /**
     * Codes ISO3 des pays couverts par KPay.
     *
     * @return string[]
     */
    public static function countryCodes(): array
    {
        return array_keys(self::COUNTRIES);
    }

    /**
     * Codes ISO2 des pays couverts (pour aligner la table `countries`).
     *
     * @return string[]
     */
    public static function iso2Codes(): array
    {
        return array_column(self::COUNTRIES, 'iso2');
    }

    /**
     * Tous les codes provider valides du catalogue.
     *
     * @return string[]
     */
    public static function providerCodes(): array
    {
        $codes = [];
        foreach (self::COUNTRIES as $country) {
            $codes = array_merge($codes, array_keys($country['providers']));
        }

        return $codes;
    }

    public static function isValidProvider(?string $providerCode): bool
    {
        return $providerCode !== null && in_array($providerCode, self::providerCodes(), true);
    }

    /**
     * Métadonnées d'un pays par son code ISO3.
     */
    public static function country(?string $iso3): ?array
    {
        if (!$iso3) {
            return null;
        }

        return self::COUNTRIES[strtoupper($iso3)] ?? null;
    }

    /**
     * Code ISO3 du pays auquel appartient un provider (ex. MTN_MOMO_CMR → CMR).
     */
    public static function countryForProvider(?string $providerCode): ?string
    {
        if (!$providerCode) {
            return null;
        }

        foreach (self::COUNTRIES as $iso3 => $country) {
            if (isset($country['providers'][$providerCode])) {
                return $iso3;
            }
        }

        return null;
    }

    /**
     * Devise d'un provider, déduite de son pays.
     *
     * Contrairement à l'ancien mapping par suffixe (qui retombait sur XAF
     * pour tout pays inconnu, faussant les montants CDF/KES/UGX/ZMW), un
     * provider hors catalogue renvoie ici `null` : l'appelant doit décider.
     */
    public static function currencyForProvider(?string $providerCode): ?string
    {
        $iso3 = self::countryForProvider($providerCode);

        return $iso3 ? self::COUNTRIES[$iso3]['currency'] : null;
    }

    /**
     * Le provider accepte-t-il des montants à décimales ?
     */
    public static function supportsDecimals(?string $providerCode): bool
    {
        $iso3 = self::countryForProvider($providerCode);

        return $iso3 ? (bool) self::COUNTRIES[$iso3]['decimals'] : false;
    }

    /**
     * Code ISO3 du pays correspondant à un numéro international (sans « + »).
     * L'indicatif le plus long gagne, pour éviter qu'un préfixe court ne
     * capture un pays dont l'indicatif commence pareil.
     */
    public static function countryForPhone(string $intlPhone): ?string
    {
        $best = null;
        $bestLength = 0;

        foreach (self::COUNTRIES as $iso3 => $country) {
            $dial = $country['dial'];
            if (str_starts_with($intlPhone, $dial) && strlen($dial) > $bestLength) {
                $best = $iso3;
                $bestLength = strlen($dial);
            }
        }

        return $best;
    }

    /**
     * Devine le provider d'un numéro international normalisé.
     *
     * Renvoie `null` si l'opérateur ne peut pas être tranché de façon fiable
     * (préfixe inconnu, ou pays à plusieurs opérateurs sans correspondance) :
     * l'appelant demande alors un `provider_code` explicite plutôt que de
     * partir sur un opérateur au hasard.
     */
    public static function guessProvider(string $intlPhone): ?string
    {
        $iso3 = self::countryForPhone($intlPhone);
        if (!$iso3) {
            return null;
        }

        $country = self::COUNTRIES[$iso3];
        $national = substr($intlPhone, strlen($country['dial']));

        foreach ($country['providers'] as $code => $meta) {
            foreach ($meta['prefixes'] as $prefix) {
                if (str_starts_with($national, $prefix)) {
                    return $code;
                }
            }
        }

        // Pays mono-opérateur : aucun doute possible même sans préfixe connu.
        if (count($country['providers']) === 1) {
            return array_key_first($country['providers']);
        }

        return null;
    }

    /**
     * Catalogue sérialisable pour le frontend : liste des pays avec leurs
     * opérateurs, prêt à alimenter les sélecteurs « pays puis opérateur ».
     *
     * @param  array<string, array<string, string>>|null  $availability
     *         Statuts renvoyés par KPay (`GET /payments/availability`),
     *         indexés provider → operationType → statut. Quand il est fourni,
     *         chaque provider porte son statut réel et `available`.
     */
    public static function toArray(?array $availability = null, string $operationType = 'DEPOSIT'): array
    {
        $countries = [];

        foreach (self::COUNTRIES as $iso3 => $country) {
            $providers = [];

            foreach ($country['providers'] as $code => $meta) {
                $status = $availability[$code][$operationType] ?? null;

                $providers[] = [
                    'code' => $code,
                    'label' => $meta['label'],
                    'status' => $status,
                    // Sans info de disponibilité, on suppose l'opérateur actif
                    // plutôt que de masquer tout le catalogue si KPay est muet.
                    'available' => $status === null ? true : $status !== 'CLOSED',
                ];
            }

            $countries[] = [
                'iso3' => $iso3,
                'iso2' => $country['iso2'],
                'name' => $country['name'],
                'dial_code' => $country['dial'],
                'currency' => $country['currency'],
                'decimals' => $country['decimals'],
                'providers' => $providers,
            ];
        }

        return $countries;
    }
}
