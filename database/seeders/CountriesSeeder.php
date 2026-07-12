<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

/**
 * Tous les pays du monde (ISO 3166-1 alpha-2) avec indicatif téléphonique
 * et emoji drapeau. Le Cameroun (CM) est la valeur par défaut de l'app.
 * Idempotent : updateOrCreate sur le code ISO.
 */
class CountriesSeeder extends Seeder
{
    public function run(): void
    {
        // [code alpha-2, code alpha-3, indicatif, drapeau emoji, nom (FR)]
        $countries = [
            ['CM', 'CMR', '237', '🇨🇲', 'Cameroun'],
            ['AF', 'AFG', '93', '🇦🇫', 'Afghanistan'],
            ['ZA', 'ZAF', '27', '🇿🇦', 'Afrique du Sud'],
            ['AL', 'ALB', '355', '🇦🇱', 'Albanie'],
            ['DZ', 'DZA', '213', '🇩🇿', 'Algérie'],
            ['DE', 'DEU', '49', '🇩🇪', 'Allemagne'],
            ['AD', 'AND', '376', '🇦🇩', 'Andorre'],
            ['AO', 'AGO', '244', '🇦🇴', 'Angola'],
            ['AI', 'AIA', '1264', '🇦🇮', 'Anguilla'],
            ['AQ', 'ATA', '672', '🇦🇶', 'Antarctique'],
            ['AG', 'ATG', '1268', '🇦🇬', 'Antigua-et-Barbuda'],
            ['SA', 'SAU', '966', '🇸🇦', 'Arabie saoudite'],
            ['AR', 'ARG', '54', '🇦🇷', 'Argentine'],
            ['AM', 'ARM', '374', '🇦🇲', 'Arménie'],
            ['AW', 'ABW', '297', '🇦🇼', 'Aruba'],
            ['AU', 'AUS', '61', '🇦🇺', 'Australie'],
            ['AT', 'AUT', '43', '🇦🇹', 'Autriche'],
            ['AZ', 'AZE', '994', '🇦🇿', 'Azerbaïdjan'],
            ['BS', 'BHS', '1242', '🇧🇸', 'Bahamas'],
            ['BH', 'BHR', '973', '🇧🇭', 'Bahreïn'],
            ['BD', 'BGD', '880', '🇧🇩', 'Bangladesh'],
            ['BB', 'BRB', '1246', '🇧🇧', 'Barbade'],
            ['BE', 'BEL', '32', '🇧🇪', 'Belgique'],
            ['BZ', 'BLZ', '501', '🇧🇿', 'Belize'],
            ['BJ', 'BEN', '229', '🇧🇯', 'Bénin'],
            ['BM', 'BMU', '1441', '🇧🇲', 'Bermudes'],
            ['BT', 'BTN', '975', '🇧🇹', 'Bhoutan'],
            ['BY', 'BLR', '375', '🇧🇾', 'Biélorussie'],
            ['BO', 'BOL', '591', '🇧🇴', 'Bolivie'],
            ['BA', 'BIH', '387', '🇧🇦', 'Bosnie-Herzégovine'],
            ['BW', 'BWA', '267', '🇧🇼', 'Botswana'],
            ['BR', 'BRA', '55', '🇧🇷', 'Brésil'],
            ['BN', 'BRN', '673', '🇧🇳', 'Brunei'],
            ['BG', 'BGR', '359', '🇧🇬', 'Bulgarie'],
            ['BF', 'BFA', '226', '🇧🇫', 'Burkina Faso'],
            ['BI', 'BDI', '257', '🇧🇮', 'Burundi'],
            ['KH', 'KHM', '855', '🇰🇭', 'Cambodge'],
            ['CA', 'CAN', '1', '🇨🇦', 'Canada'],
            ['CV', 'CPV', '238', '🇨🇻', 'Cap-Vert'],
            ['CL', 'CHL', '56', '🇨🇱', 'Chili'],
            ['CN', 'CHN', '86', '🇨🇳', 'Chine'],
            ['CY', 'CYP', '357', '🇨🇾', 'Chypre'],
            ['CO', 'COL', '57', '🇨🇴', 'Colombie'],
            ['KM', 'COM', '269', '🇰🇲', 'Comores'],
            ['CG', 'COG', '242', '🇨🇬', 'Congo'],
            ['CD', 'COD', '243', '🇨🇩', 'Congo (RDC)'],
            ['KR', 'KOR', '82', '🇰🇷', 'Corée du Sud'],
            ['KP', 'PRK', '850', '🇰🇵', 'Corée du Nord'],
            ['CR', 'CRI', '506', '🇨🇷', 'Costa Rica'],
            ['CI', 'CIV', '225', '🇨🇮', "Côte d'Ivoire"],
            ['HR', 'HRV', '385', '🇭🇷', 'Croatie'],
            ['CU', 'CUB', '53', '🇨🇺', 'Cuba'],
            ['CW', 'CUW', '599', '🇨🇼', 'Curaçao'],
            ['DK', 'DNK', '45', '🇩🇰', 'Danemark'],
            ['DJ', 'DJI', '253', '🇩🇯', 'Djibouti'],
            ['DM', 'DMA', '1767', '🇩🇲', 'Dominique'],
            ['EG', 'EGY', '20', '🇪🇬', 'Égypte'],
            ['AE', 'ARE', '971', '🇦🇪', 'Émirats arabes unis'],
            ['EC', 'ECU', '593', '🇪🇨', 'Équateur'],
            ['ER', 'ERI', '291', '🇪🇷', 'Érythrée'],
            ['ES', 'ESP', '34', '🇪🇸', 'Espagne'],
            ['EE', 'EST', '372', '🇪🇪', 'Estonie'],
            ['SZ', 'SWZ', '268', '🇸🇿', 'Eswatini'],
            ['US', 'USA', '1', '🇺🇸', 'États-Unis'],
            ['ET', 'ETH', '251', '🇪🇹', 'Éthiopie'],
            ['FJ', 'FJI', '679', '🇫🇯', 'Fidji'],
            ['FI', 'FIN', '358', '🇫🇮', 'Finlande'],
            ['FR', 'FRA', '33', '🇫🇷', 'France'],
            ['GA', 'GAB', '241', '🇬🇦', 'Gabon'],
            ['GM', 'GMB', '220', '🇬🇲', 'Gambie'],
            ['GE', 'GEO', '995', '🇬🇪', 'Géorgie'],
            ['GH', 'GHA', '233', '🇬🇭', 'Ghana'],
            ['GI', 'GIB', '350', '🇬🇮', 'Gibraltar'],
            ['GR', 'GRC', '30', '🇬🇷', 'Grèce'],
            ['GD', 'GRD', '1473', '🇬🇩', 'Grenade'],
            ['GL', 'GRL', '299', '🇬🇱', 'Groenland'],
            ['GP', 'GLP', '590', '🇬🇵', 'Guadeloupe'],
            ['GU', 'GUM', '1671', '🇬🇺', 'Guam'],
            ['GT', 'GTM', '502', '🇬🇹', 'Guatemala'],
            ['GG', 'GGY', '44', '🇬🇬', 'Guernesey'],
            ['GN', 'GIN', '224', '🇬🇳', 'Guinée'],
            ['GW', 'GNB', '245', '🇬🇼', 'Guinée-Bissau'],
            ['GQ', 'GNQ', '240', '🇬🇶', 'Guinée équatoriale'],
            ['GY', 'GUY', '592', '🇬🇾', 'Guyana'],
            ['GF', 'GUF', '594', '🇬🇫', 'Guyane'],
            ['HT', 'HTI', '509', '🇭🇹', 'Haïti'],
            ['HN', 'HND', '504', '🇭🇳', 'Honduras'],
            ['HK', 'HKG', '852', '🇭🇰', 'Hong Kong'],
            ['HU', 'HUN', '36', '🇭🇺', 'Hongrie'],
            ['IN', 'IND', '91', '🇮🇳', 'Inde'],
            ['ID', 'IDN', '62', '🇮🇩', 'Indonésie'],
            ['IQ', 'IRQ', '964', '🇮🇶', 'Irak'],
            ['IR', 'IRN', '98', '🇮🇷', 'Iran'],
            ['IE', 'IRL', '353', '🇮🇪', 'Irlande'],
            ['IS', 'ISL', '354', '🇮🇸', 'Islande'],
            ['IL', 'ISR', '972', '🇮🇱', 'Israël'],
            ['IT', 'ITA', '39', '🇮🇹', 'Italie'],
            ['JM', 'JAM', '1876', '🇯🇲', 'Jamaïque'],
            ['JP', 'JPN', '81', '🇯🇵', 'Japon'],
            ['JE', 'JEY', '44', '🇯🇪', 'Jersey'],
            ['JO', 'JOR', '962', '🇯🇴', 'Jordanie'],
            ['KZ', 'KAZ', '7', '🇰🇿', 'Kazakhstan'],
            ['KE', 'KEN', '254', '🇰🇪', 'Kenya'],
            ['KG', 'KGZ', '996', '🇰🇬', 'Kirghizistan'],
            ['KI', 'KIR', '686', '🇰🇮', 'Kiribati'],
            ['KW', 'KWT', '965', '🇰🇼', 'Koweït'],
            ['LA', 'LAO', '856', '🇱🇦', 'Laos'],
            ['LS', 'LSO', '266', '🇱🇸', 'Lesotho'],
            ['LV', 'LVA', '371', '🇱🇻', 'Lettonie'],
            ['LB', 'LBN', '961', '🇱🇧', 'Liban'],
            ['LR', 'LBR', '231', '🇱🇷', 'Liberia'],
            ['LY', 'LBY', '218', '🇱🇾', 'Libye'],
            ['LI', 'LIE', '423', '🇱🇮', 'Liechtenstein'],
            ['LT', 'LTU', '370', '🇱🇹', 'Lituanie'],
            ['LU', 'LUX', '352', '🇱🇺', 'Luxembourg'],
            ['MO', 'MAC', '853', '🇲🇴', 'Macao'],
            ['MK', 'MKD', '389', '🇲🇰', 'Macédoine du Nord'],
            ['MG', 'MDG', '261', '🇲🇬', 'Madagascar'],
            ['MY', 'MYS', '60', '🇲🇾', 'Malaisie'],
            ['MW', 'MWI', '265', '🇲🇼', 'Malawi'],
            ['MV', 'MDV', '960', '🇲🇻', 'Maldives'],
            ['ML', 'MLI', '223', '🇲🇱', 'Mali'],
            ['MT', 'MLT', '356', '🇲🇹', 'Malte'],
            ['MA', 'MAR', '212', '🇲🇦', 'Maroc'],
            ['MH', 'MHL', '692', '🇲🇭', 'Îles Marshall'],
            ['MQ', 'MTQ', '596', '🇲🇶', 'Martinique'],
            ['MU', 'MUS', '230', '🇲🇺', 'Maurice'],
            ['MR', 'MRT', '222', '🇲🇷', 'Mauritanie'],
            ['YT', 'MYT', '262', '🇾🇹', 'Mayotte'],
            ['MX', 'MEX', '52', '🇲🇽', 'Mexique'],
            ['FM', 'FSM', '691', '🇫🇲', 'Micronésie'],
            ['MD', 'MDA', '373', '🇲🇩', 'Moldavie'],
            ['MC', 'MCO', '377', '🇲🇨', 'Monaco'],
            ['MN', 'MNG', '976', '🇲🇳', 'Mongolie'],
            ['ME', 'MNE', '382', '🇲🇪', 'Monténégro'],
            ['MS', 'MSR', '1664', '🇲🇸', 'Montserrat'],
            ['MZ', 'MOZ', '258', '🇲🇿', 'Mozambique'],
            ['MM', 'MMR', '95', '🇲🇲', 'Myanmar'],
            ['NA', 'NAM', '264', '🇳🇦', 'Namibie'],
            ['NR', 'NRU', '674', '🇳🇷', 'Nauru'],
            ['NP', 'NPL', '977', '🇳🇵', 'Népal'],
            ['NI', 'NIC', '505', '🇳🇮', 'Nicaragua'],
            ['NE', 'NER', '227', '🇳🇪', 'Niger'],
            ['NG', 'NGA', '234', '🇳🇬', 'Nigeria'],
            ['NU', 'NIU', '683', '🇳🇺', 'Niue'],
            ['NO', 'NOR', '47', '🇳🇴', 'Norvège'],
            ['NC', 'NCL', '687', '🇳🇨', 'Nouvelle-Calédonie'],
            ['NZ', 'NZL', '64', '🇳🇿', 'Nouvelle-Zélande'],
            ['OM', 'OMN', '968', '🇴🇲', 'Oman'],
            ['UG', 'UGA', '256', '🇺🇬', 'Ouganda'],
            ['UZ', 'UZB', '998', '🇺🇿', 'Ouzbékistan'],
            ['PK', 'PAK', '92', '🇵🇰', 'Pakistan'],
            ['PW', 'PLW', '680', '🇵🇼', 'Palaos'],
            ['PS', 'PSE', '970', '🇵🇸', 'Palestine'],
            ['PA', 'PAN', '507', '🇵🇦', 'Panama'],
            ['PG', 'PNG', '675', '🇵🇬', 'Papouasie-Nouvelle-Guinée'],
            ['PY', 'PRY', '595', '🇵🇾', 'Paraguay'],
            ['NL', 'NLD', '31', '🇳🇱', 'Pays-Bas'],
            ['PE', 'PER', '51', '🇵🇪', 'Pérou'],
            ['PH', 'PHL', '63', '🇵🇭', 'Philippines'],
            ['PL', 'POL', '48', '🇵🇱', 'Pologne'],
            ['PF', 'PYF', '689', '🇵🇫', 'Polynésie française'],
            ['PR', 'PRI', '1787', '🇵🇷', 'Porto Rico'],
            ['PT', 'PRT', '351', '🇵🇹', 'Portugal'],
            ['QA', 'QAT', '974', '🇶🇦', 'Qatar'],
            ['RE', 'REU', '262', '🇷🇪', 'La Réunion'],
            ['RO', 'ROU', '40', '🇷🇴', 'Roumanie'],
            ['GB', 'GBR', '44', '🇬🇧', 'Royaume-Uni'],
            ['RU', 'RUS', '7', '🇷🇺', 'Russie'],
            ['RW', 'RWA', '250', '🇷🇼', 'Rwanda'],
            ['SV', 'SLV', '503', '🇸🇻', 'Salvador'],
            ['WS', 'WSM', '685', '🇼🇸', 'Samoa'],
            ['AS', 'ASM', '1684', '🇦🇸', 'Samoa américaines'],
            ['SM', 'SMR', '378', '🇸🇲', 'Saint-Marin'],
            ['SH', 'SHN', '290', '🇸🇭', 'Sainte-Hélène'],
            ['LC', 'LCA', '1758', '🇱🇨', 'Sainte-Lucie'],
            ['KN', 'KNA', '1869', '🇰🇳', 'Saint-Kitts-et-Nevis'],
            ['VC', 'VCT', '1784', '🇻🇨', 'Saint-Vincent-et-les-Grenadines'],
            ['ST', 'STP', '239', '🇸🇹', 'Sao Tomé-et-Principe'],
            ['SN', 'SEN', '221', '🇸🇳', 'Sénégal'],
            ['RS', 'SRB', '381', '🇷🇸', 'Serbie'],
            ['SC', 'SYC', '248', '🇸🇨', 'Seychelles'],
            ['SL', 'SLE', '232', '🇸🇱', 'Sierra Leone'],
            ['SG', 'SGP', '65', '🇸🇬', 'Singapour'],
            ['SK', 'SVK', '421', '🇸🇰', 'Slovaquie'],
            ['SI', 'SVN', '386', '🇸🇮', 'Slovénie'],
            ['SO', 'SOM', '252', '🇸🇴', 'Somalie'],
            ['SD', 'SDN', '249', '🇸🇩', 'Soudan'],
            ['SS', 'SSD', '211', '🇸🇸', 'Soudan du Sud'],
            ['LK', 'LKA', '94', '🇱🇰', 'Sri Lanka'],
            ['SE', 'SWE', '46', '🇸🇪', 'Suède'],
            ['CH', 'CHE', '41', '🇨🇭', 'Suisse'],
            ['SR', 'SUR', '597', '🇸🇷', 'Suriname'],
            ['SY', 'SYR', '963', '🇸🇾', 'Syrie'],
            ['TJ', 'TJK', '992', '🇹🇯', 'Tadjikistan'],
            ['TW', 'TWN', '886', '🇹🇼', 'Taïwan'],
            ['TZ', 'TZA', '255', '🇹🇿', 'Tanzanie'],
            ['TD', 'TCD', '235', '🇹🇩', 'Tchad'],
            ['CZ', 'CZE', '420', '🇨🇿', 'Tchéquie'],
            ['TH', 'THA', '66', '🇹🇭', 'Thaïlande'],
            ['TL', 'TLS', '670', '🇹🇱', 'Timor oriental'],
            ['TG', 'TGO', '228', '🇹🇬', 'Togo'],
            ['TK', 'TKL', '690', '🇹🇰', 'Tokelau'],
            ['TO', 'TON', '676', '🇹🇴', 'Tonga'],
            ['TT', 'TTO', '1868', '🇹🇹', 'Trinité-et-Tobago'],
            ['TN', 'TUN', '216', '🇹🇳', 'Tunisie'],
            ['TM', 'TKM', '993', '🇹🇲', 'Turkménistan'],
            ['TR', 'TUR', '90', '🇹🇷', 'Turquie'],
            ['TV', 'TUV', '688', '🇹🇻', 'Tuvalu'],
            ['UA', 'UKR', '380', '🇺🇦', 'Ukraine'],
            ['UY', 'URY', '598', '🇺🇾', 'Uruguay'],
            ['VU', 'VUT', '678', '🇻🇺', 'Vanuatu'],
            ['VA', 'VAT', '379', '🇻🇦', 'Vatican'],
            ['VE', 'VEN', '58', '🇻🇪', 'Venezuela'],
            ['VN', 'VNM', '84', '🇻🇳', 'Vietnam'],
            ['YE', 'YEM', '967', '🇾🇪', 'Yémen'],
            ['ZM', 'ZMB', '260', '🇿🇲', 'Zambie'],
            ['ZW', 'ZWE', '263', '🇿🇼', 'Zimbabwe'],
        ];

        $kpaySet = array_flip(self::KPAY_COUNTRIES);

        foreach ($countries as $c) {
            Country::updateOrCreate(
                ['code' => $c[0]],
                [
                    'iso3' => $c[1],
                    'dial_code' => $c[2],
                    'flag' => $c[3],
                    'currency' => self::CURRENCIES[$c[0]] ?? null, // devise ISO 4217
                    'supports_kpay' => isset($kpaySet[$c[0]]),      // KPay opérationnel
                    'name' => $c[4],
                    'is_active' => true,
                ]
            );
        }
    }

    /**
     * Pays où KPay (Mobile Money) est opérationnel — seuls activables dans le
     * sélecteur mobile pour l'instant (les autres sont grisés). Codes ISO
     * alpha-2, dérivés de kpay_countries.dart (20 pays). Source de vérité DB :
     * élargir = ajouter un code ici puis reseeder (ou flag admin).
     */
    private const KPAY_COUNTRIES = [
        'BJ', // Bénin
        'BF', // Burkina Faso
        'CI', // Côte d'Ivoire
        'CM', // Cameroun
        'CD', // RD Congo
        'CG', // Congo
        'ET', // Éthiopie
        'GA', // Gabon
        'GH', // Ghana
        'KE', // Kenya
        'LS', // Lesotho
        'MZ', // Mozambique
        'MW', // Malawi
        'NG', // Nigeria
        'RW', // Rwanda
        'SN', // Sénégal
        'SL', // Sierra Leone
        'TZ', // Tanzanie
        'UG', // Ouganda
        'ZM', // Zambie
    ];

    /**
     * Devise (ISO 4217) par pays (clé = code ISO alpha-2).
     * Sert à pré-remplir users.preferred_currency au choix du pays.
     * Les pays de la zone franc utilisent XAF (Afrique centrale) ou
     * XOF (Afrique de l'Ouest) — libellés "FCFA" côté UI.
     */
    private const CURRENCIES = [
        // Zone franc CFA — Afrique centrale (CEMAC) → XAF
        'CM' => 'XAF', 'GA' => 'XAF', 'CG' => 'XAF', 'CF' => 'XAF',
        'TD' => 'XAF', 'GQ' => 'XAF',
        // Zone franc CFA — Afrique de l'Ouest (UEMOA) → XOF
        'CI' => 'XOF', 'SN' => 'XOF', 'BJ' => 'XOF', 'BF' => 'XOF',
        'TG' => 'XOF', 'NE' => 'XOF', 'ML' => 'XOF', 'GW' => 'XOF',
        // Reste de l'Afrique
        'CD' => 'CDF', 'GN' => 'GNF', 'GH' => 'GHS', 'NG' => 'NGN',
        'SL' => 'SLL', 'LR' => 'LRD', 'MR' => 'MRU', 'GM' => 'GMD',
        'MA' => 'MAD', 'DZ' => 'DZD', 'TN' => 'TND', 'LY' => 'LYD',
        'EG' => 'EGP', 'KE' => 'KES', 'TZ' => 'TZS', 'UG' => 'UGX',
        'RW' => 'RWF', 'BI' => 'BIF', 'ET' => 'ETB', 'SO' => 'SOS',
        'DJ' => 'DJF', 'ZA' => 'ZAR', 'BW' => 'BWP', 'NA' => 'NAD',
        'ZW' => 'ZWL', 'ZM' => 'ZMW', 'MW' => 'MWK', 'MZ' => 'MZN',
        'AO' => 'AOA', 'MG' => 'MGA', 'MU' => 'MUR', 'SC' => 'SCR',
        'CV' => 'CVE', 'KM' => 'KMF', 'ST' => 'STN', 'SD' => 'SDG',
        'SS' => 'SSP', 'ER' => 'ERN', 'LS' => 'LSL', 'SZ' => 'SZL',
        // Zone euro
        'FR' => 'EUR', 'BE' => 'EUR', 'DE' => 'EUR', 'IT' => 'EUR',
        'ES' => 'EUR', 'PT' => 'EUR', 'NL' => 'EUR', 'IE' => 'EUR',
        'AT' => 'EUR', 'FI' => 'EUR', 'GR' => 'EUR', 'LU' => 'EUR',
        'SK' => 'EUR', 'SI' => 'EUR', 'EE' => 'EUR', 'LV' => 'EUR',
        'LT' => 'EUR', 'CY' => 'EUR', 'MT' => 'EUR', 'MC' => 'EUR',
        'AD' => 'EUR', 'SM' => 'EUR', 'VA' => 'EUR', 'ME' => 'EUR',
        // Reste de l'Europe
        'CH' => 'CHF', 'LI' => 'CHF', 'GB' => 'GBP', 'GG' => 'GBP',
        'JE' => 'GBP', 'GI' => 'GIP', 'DK' => 'DKK', 'SE' => 'SEK',
        'NO' => 'NOK', 'IS' => 'ISK', 'PL' => 'PLN', 'CZ' => 'CZK',
        'HU' => 'HUF', 'RO' => 'RON', 'BG' => 'BGN', 'HR' => 'EUR',
        'RS' => 'RSD', 'BA' => 'BAM', 'MK' => 'MKD', 'AL' => 'ALL',
        'MD' => 'MDL', 'UA' => 'UAH', 'BY' => 'BYN', 'RU' => 'RUB',
        // Amériques
        'US' => 'USD', 'CA' => 'CAD', 'MX' => 'MXN', 'BR' => 'BRL',
        'AR' => 'ARS', 'CL' => 'CLP', 'CO' => 'COP', 'PE' => 'PEN',
        'VE' => 'VES', 'EC' => 'USD', 'BO' => 'BOB', 'PY' => 'PYG',
        'UY' => 'UYU', 'GY' => 'GYD', 'SR' => 'SRD', 'GF' => 'EUR',
        'PA' => 'PAB', 'CR' => 'CRC', 'NI' => 'NIO', 'HN' => 'HNL',
        'SV' => 'USD', 'GT' => 'GTQ', 'BZ' => 'BZD', 'CU' => 'CUP',
        'DO' => 'DOP', 'HT' => 'HTG', 'JM' => 'JMD', 'TT' => 'TTD',
        'BB' => 'BBD', 'BS' => 'BSD', 'PR' => 'USD',
        // Moyen-Orient
        'SA' => 'SAR', 'AE' => 'AED', 'QA' => 'QAR', 'KW' => 'KWD',
        'BH' => 'BHD', 'OM' => 'OMR', 'JO' => 'JOD', 'LB' => 'LBP',
        'IL' => 'ILS', 'PS' => 'ILS', 'IQ' => 'IQD', 'IR' => 'IRR',
        'SY' => 'SYP', 'YE' => 'YER', 'TR' => 'TRY',
        // Asie
        'CN' => 'CNY', 'JP' => 'JPY', 'KR' => 'KRW', 'KP' => 'KPW',
        'IN' => 'INR', 'PK' => 'PKR', 'BD' => 'BDT', 'LK' => 'LKR',
        'NP' => 'NPR', 'BT' => 'BTN', 'MV' => 'MVR', 'AF' => 'AFN',
        'ID' => 'IDR', 'MY' => 'MYR', 'SG' => 'SGD', 'TH' => 'THB',
        'VN' => 'VND', 'PH' => 'PHP', 'MM' => 'MMK', 'KH' => 'KHR',
        'LA' => 'LAK', 'BN' => 'BND', 'TL' => 'USD', 'MN' => 'MNT',
        'KZ' => 'KZT', 'UZ' => 'UZS', 'TM' => 'TMT', 'KG' => 'KGS',
        'TJ' => 'TJS', 'AZ' => 'AZN', 'AM' => 'AMD', 'GE' => 'GEL',
        'HK' => 'HKD', 'MO' => 'MOP', 'TW' => 'TWD',
        // Océanie
        'AU' => 'AUD', 'NZ' => 'NZD', 'FJ' => 'FJD', 'PG' => 'PGK',
        'WS' => 'WST', 'TO' => 'TOP', 'VU' => 'VUV', 'SB' => 'SBD',
        'NC' => 'XPF', 'PF' => 'XPF', 'KI' => 'AUD', 'NR' => 'AUD',
        'TV' => 'AUD', 'NU' => 'NZD', 'TK' => 'NZD', 'FM' => 'USD',
        'MH' => 'USD', 'PW' => 'USD', 'GU' => 'USD', 'AS' => 'USD',
        // Territoires & Caraïbes
        'AG' => 'XCD', 'DM' => 'XCD', 'GD' => 'XCD', 'KN' => 'XCD',
        'LC' => 'XCD', 'VC' => 'XCD', 'AI' => 'XCD', 'MS' => 'XCD',
        'AW' => 'AWG', 'CW' => 'ANG', 'BM' => 'BMD',
        // Territoires français d'outre-mer → EUR
        'GP' => 'EUR', 'MQ' => 'EUR', 'RE' => 'EUR', 'YT' => 'EUR',
        // Autres territoires
        'GL' => 'DKK', 'SH' => 'SHP', 'AQ' => 'USD',
    ];
}
