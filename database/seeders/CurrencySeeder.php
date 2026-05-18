<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        // Liste des devises mondiales (ISO 4217). XAF en premier (devise locale).
        $currencies = [
            ['code' => 'XAF', 'name' => 'Franc CFA (BEAC)', 'symbol' => 'FCFA'],
            ['code' => 'XOF', 'name' => 'Franc CFA (BCEAO)', 'symbol' => 'CFA'],
            ['code' => 'USD', 'name' => 'Dollar américain', 'symbol' => '$'],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            ['code' => 'GBP', 'name' => 'Livre sterling', 'symbol' => '£'],
            ['code' => 'NGN', 'name' => 'Naira nigérian', 'symbol' => '₦'],
            ['code' => 'GHS', 'name' => 'Cedi ghanéen', 'symbol' => 'GH₵'],
            ['code' => 'ZAR', 'name' => 'Rand sud-africain', 'symbol' => 'R'],
            ['code' => 'MAD', 'name' => 'Dirham marocain', 'symbol' => 'DH'],
            ['code' => 'DZD', 'name' => 'Dinar algérien', 'symbol' => 'DA'],
            ['code' => 'TND', 'name' => 'Dinar tunisien', 'symbol' => 'DT'],
            ['code' => 'EGP', 'name' => 'Livre égyptienne', 'symbol' => 'E£'],
            ['code' => 'KES', 'name' => 'Shilling kényan', 'symbol' => 'KSh'],
            ['code' => 'CDF', 'name' => 'Franc congolais', 'symbol' => 'FC'],
            ['code' => 'RWF', 'name' => 'Franc rwandais', 'symbol' => 'FRw'],
            ['code' => 'CAD', 'name' => 'Dollar canadien', 'symbol' => 'CA$'],
            ['code' => 'CHF', 'name' => 'Franc suisse', 'symbol' => 'CHF'],
            ['code' => 'CNY', 'name' => 'Yuan chinois', 'symbol' => '¥'],
            ['code' => 'JPY', 'name' => 'Yen japonais', 'symbol' => '¥'],
            ['code' => 'INR', 'name' => 'Roupie indienne', 'symbol' => '₹'],
            ['code' => 'AUD', 'name' => 'Dollar australien', 'symbol' => 'A$'],
            ['code' => 'AED', 'name' => 'Dirham des Émirats', 'symbol' => 'د.إ'],
            ['code' => 'SAR', 'name' => 'Riyal saoudien', 'symbol' => 'ر.س'],
            ['code' => 'BRL', 'name' => 'Real brésilien', 'symbol' => 'R$'],
            ['code' => 'RUB', 'name' => 'Rouble russe', 'symbol' => '₽'],
            ['code' => 'TRY', 'name' => 'Livre turque', 'symbol' => '₺'],
            ['code' => 'SGD', 'name' => 'Dollar de Singapour', 'symbol' => 'S$'],
            ['code' => 'HKD', 'name' => 'Dollar de Hong Kong', 'symbol' => 'HK$'],
            ['code' => 'SEK', 'name' => 'Couronne suédoise', 'symbol' => 'kr'],
            ['code' => 'NOK', 'name' => 'Couronne norvégienne', 'symbol' => 'kr'],
            ['code' => 'DKK', 'name' => 'Couronne danoise', 'symbol' => 'kr'],
            ['code' => 'PLN', 'name' => 'Zloty polonais', 'symbol' => 'zł'],
            ['code' => 'MXN', 'name' => 'Peso mexicain', 'symbol' => 'Mex$'],
            ['code' => 'KRW', 'name' => 'Won sud-coréen', 'symbol' => '₩'],
            ['code' => 'IDR', 'name' => 'Roupie indonésienne', 'symbol' => 'Rp'],
            ['code' => 'THB', 'name' => 'Baht thaïlandais', 'symbol' => '฿'],
            ['code' => 'MYR', 'name' => 'Ringgit malaisien', 'symbol' => 'RM'],
            ['code' => 'PHP', 'name' => 'Peso philippin', 'symbol' => '₱'],
            ['code' => 'VND', 'name' => 'Dong vietnamien', 'symbol' => '₫'],
            ['code' => 'NZD', 'name' => 'Dollar néo-zélandais', 'symbol' => 'NZ$'],
            ['code' => 'ARS', 'name' => 'Peso argentin', 'symbol' => 'AR$'],
            ['code' => 'CLP', 'name' => 'Peso chilien', 'symbol' => 'CL$'],
            ['code' => 'COP', 'name' => 'Peso colombien', 'symbol' => 'CO$'],
            ['code' => 'ILS', 'name' => 'Shekel israélien', 'symbol' => '₪'],
            ['code' => 'QAR', 'name' => 'Riyal qatari', 'symbol' => 'ر.ق'],
            ['code' => 'KWD', 'name' => 'Dinar koweïtien', 'symbol' => 'د.ك'],
            ['code' => 'XPF', 'name' => 'Franc Pacifique', 'symbol' => '₣'],
            ['code' => 'ANG', 'name' => 'Florin antillais', 'symbol' => 'ƒ'],
            ['code' => 'MUR', 'name' => 'Roupie mauricienne', 'symbol' => '₨'],
            ['code' => 'AOA', 'name' => 'Kwanza angolais', 'symbol' => 'Kz'],
            ['code' => 'ETB', 'name' => 'Birr éthiopien', 'symbol' => 'Br'],
            ['code' => 'GNF', 'name' => 'Franc guinéen', 'symbol' => 'FG'],
            ['code' => 'TZS', 'name' => 'Shilling tanzanien', 'symbol' => 'TSh'],
            ['code' => 'UGX', 'name' => 'Shilling ougandais', 'symbol' => 'USh'],
            ['code' => 'ZMW', 'name' => 'Kwacha zambien', 'symbol' => 'ZK'],
            ['code' => 'BWP', 'name' => 'Pula botswanais', 'symbol' => 'P'],
            ['code' => 'MZN', 'name' => 'Metical mozambicain', 'symbol' => 'MT'],
            ['code' => 'MGA', 'name' => 'Ariary malgache', 'symbol' => 'Ar'],
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(
                ['code' => $currency['code']],
                [
                    'name' => $currency['name'],
                    'symbol' => $currency['symbol'],
                    'is_active' => true,
                ]
            );
        }
    }
}
