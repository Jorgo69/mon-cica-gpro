<?php

/**
 * Liste des pays avec code ISO, noms FR/EN, prefixe telephonique et nombre de digits.
 * Usage : config('countries') → array de pays
 * Usage : collect(config('countries'))->firstWhere('code', 'BJ') → Benin
 *
 * Priorite : Afrique de l'Ouest/Centrale en premier, puis reste du monde alphabetique.
 */

return [

    // ═══════════════════════════════════════════════════
    // AFRIQUE DE L'OUEST (CEDEAO + Mauritanie)
    // ═══════════════════════════════════════════════════
    ['code' => 'BJ', 'name_fr' => 'Benin', 'name_en' => 'Benin', 'prefix' => '+229', 'digits' => 10, 'flag' => '🇧🇯'],
    ['code' => 'BF', 'name_fr' => 'Burkina Faso', 'name_en' => 'Burkina Faso', 'prefix' => '+226', 'digits' => 8, 'flag' => '🇧🇫'],
    ['code' => 'CV', 'name_fr' => 'Cap-Vert', 'name_en' => 'Cape Verde', 'prefix' => '+238', 'digits' => 7, 'flag' => '🇨🇻'],
    ['code' => 'CI', 'name_fr' => 'Cote d\'Ivoire', 'name_en' => 'Ivory Coast', 'prefix' => '+225', 'digits' => 10, 'flag' => '🇨🇮'],
    ['code' => 'GM', 'name_fr' => 'Gambie', 'name_en' => 'Gambia', 'prefix' => '+220', 'digits' => 7, 'flag' => '🇬🇲'],
    ['code' => 'GH', 'name_fr' => 'Ghana', 'name_en' => 'Ghana', 'prefix' => '+233', 'digits' => 9, 'flag' => '🇬🇭'],
    ['code' => 'GN', 'name_fr' => 'Guinee', 'name_en' => 'Guinea', 'prefix' => '+224', 'digits' => 9, 'flag' => '🇬🇳'],
    ['code' => 'GW', 'name_fr' => 'Guinee-Bissau', 'name_en' => 'Guinea-Bissau', 'prefix' => '+245', 'digits' => 7, 'flag' => '🇬🇼'],
    ['code' => 'LR', 'name_fr' => 'Liberia', 'name_en' => 'Liberia', 'prefix' => '+231', 'digits' => 7, 'flag' => '🇱🇷'],
    ['code' => 'ML', 'name_fr' => 'Mali', 'name_en' => 'Mali', 'prefix' => '+223', 'digits' => 8, 'flag' => '🇲🇱'],
    ['code' => 'MR', 'name_fr' => 'Mauritanie', 'name_en' => 'Mauritania', 'prefix' => '+222', 'digits' => 8, 'flag' => '🇲🇷'],
    ['code' => 'NE', 'name_fr' => 'Niger', 'name_en' => 'Niger', 'prefix' => '+227', 'digits' => 8, 'flag' => '🇳🇪'],
    ['code' => 'NG', 'name_fr' => 'Nigeria', 'name_en' => 'Nigeria', 'prefix' => '+234', 'digits' => 10, 'flag' => '🇳🇬'],
    ['code' => 'SN', 'name_fr' => 'Senegal', 'name_en' => 'Senegal', 'prefix' => '+221', 'digits' => 9, 'flag' => '🇸🇳'],
    ['code' => 'SL', 'name_fr' => 'Sierra Leone', 'name_en' => 'Sierra Leone', 'prefix' => '+232', 'digits' => 8, 'flag' => '🇸🇱'],
    ['code' => 'TG', 'name_fr' => 'Togo', 'name_en' => 'Togo', 'prefix' => '+228', 'digits' => 8, 'flag' => '🇹🇬'],

    // ═══════════════════════════════════════════════════
    // AFRIQUE CENTRALE (CEMAC + voisins)
    // ═══════════════════════════════════════════════════
    ['code' => 'CM', 'name_fr' => 'Cameroun', 'name_en' => 'Cameroon', 'prefix' => '+237', 'digits' => 9, 'flag' => '🇨🇲'],
    ['code' => 'CF', 'name_fr' => 'Centrafrique', 'name_en' => 'Central African Republic', 'prefix' => '+236', 'digits' => 8, 'flag' => '🇨🇫'],
    ['code' => 'TD', 'name_fr' => 'Tchad', 'name_en' => 'Chad', 'prefix' => '+235', 'digits' => 8, 'flag' => '🇹🇩'],
    ['code' => 'CG', 'name_fr' => 'Congo', 'name_en' => 'Congo', 'prefix' => '+242', 'digits' => 9, 'flag' => '🇨🇬'],
    ['code' => 'CD', 'name_fr' => 'RD Congo', 'name_en' => 'DR Congo', 'prefix' => '+243', 'digits' => 9, 'flag' => '🇨🇩'],
    ['code' => 'GA', 'name_fr' => 'Gabon', 'name_en' => 'Gabon', 'prefix' => '+241', 'digits' => 8, 'flag' => '🇬🇦'],
    ['code' => 'GQ', 'name_fr' => 'Guinee equatoriale', 'name_en' => 'Equatorial Guinea', 'prefix' => '+240', 'digits' => 9, 'flag' => '🇬🇶'],
    ['code' => 'ST', 'name_fr' => 'Sao Tome-et-Principe', 'name_en' => 'Sao Tome and Principe', 'prefix' => '+239', 'digits' => 7, 'flag' => '🇸🇹'],

    // ═══════════════════════════════════════════════════
    // AFRIQUE DE L'EST + AUSTRALE
    // ═══════════════════════════════════════════════════
    ['code' => 'BI', 'name_fr' => 'Burundi', 'name_en' => 'Burundi', 'prefix' => '+257', 'digits' => 8, 'flag' => '🇧🇮'],
    ['code' => 'KM', 'name_fr' => 'Comores', 'name_en' => 'Comoros', 'prefix' => '+269', 'digits' => 7, 'flag' => '🇰🇲'],
    ['code' => 'DJ', 'name_fr' => 'Djibouti', 'name_en' => 'Djibouti', 'prefix' => '+253', 'digits' => 8, 'flag' => '🇩🇯'],
    ['code' => 'ER', 'name_fr' => 'Erythree', 'name_en' => 'Eritrea', 'prefix' => '+291', 'digits' => 7, 'flag' => '🇪🇷'],
    ['code' => 'ET', 'name_fr' => 'Ethiopie', 'name_en' => 'Ethiopia', 'prefix' => '+251', 'digits' => 9, 'flag' => '🇪🇹'],
    ['code' => 'KE', 'name_fr' => 'Kenya', 'name_en' => 'Kenya', 'prefix' => '+254', 'digits' => 9, 'flag' => '🇰🇪'],
    ['code' => 'MG', 'name_fr' => 'Madagascar', 'name_en' => 'Madagascar', 'prefix' => '+261', 'digits' => 9, 'flag' => '🇲🇬'],
    ['code' => 'MW', 'name_fr' => 'Malawi', 'name_en' => 'Malawi', 'prefix' => '+265', 'digits' => 9, 'flag' => '🇲🇼'],
    ['code' => 'MU', 'name_fr' => 'Maurice', 'name_en' => 'Mauritius', 'prefix' => '+230', 'digits' => 8, 'flag' => '🇲🇺'],
    ['code' => 'MZ', 'name_fr' => 'Mozambique', 'name_en' => 'Mozambique', 'prefix' => '+258', 'digits' => 9, 'flag' => '🇲🇿'],
    ['code' => 'RW', 'name_fr' => 'Rwanda', 'name_en' => 'Rwanda', 'prefix' => '+250', 'digits' => 9, 'flag' => '🇷🇼'],
    ['code' => 'SC', 'name_fr' => 'Seychelles', 'name_en' => 'Seychelles', 'prefix' => '+248', 'digits' => 7, 'flag' => '🇸🇨'],
    ['code' => 'SO', 'name_fr' => 'Somalie', 'name_en' => 'Somalia', 'prefix' => '+252', 'digits' => 8, 'flag' => '🇸🇴'],
    ['code' => 'SS', 'name_fr' => 'Soudan du Sud', 'name_en' => 'South Sudan', 'prefix' => '+211', 'digits' => 9, 'flag' => '🇸🇸'],
    ['code' => 'SD', 'name_fr' => 'Soudan', 'name_en' => 'Sudan', 'prefix' => '+249', 'digits' => 9, 'flag' => '🇸🇩'],
    ['code' => 'TZ', 'name_fr' => 'Tanzanie', 'name_en' => 'Tanzania', 'prefix' => '+255', 'digits' => 9, 'flag' => '🇹🇿'],
    ['code' => 'UG', 'name_fr' => 'Ouganda', 'name_en' => 'Uganda', 'prefix' => '+256', 'digits' => 9, 'flag' => '🇺🇬'],
    ['code' => 'ZM', 'name_fr' => 'Zambie', 'name_en' => 'Zambia', 'prefix' => '+260', 'digits' => 9, 'flag' => '🇿🇲'],
    ['code' => 'ZW', 'name_fr' => 'Zimbabwe', 'name_en' => 'Zimbabwe', 'prefix' => '+263', 'digits' => 9, 'flag' => '🇿🇼'],

    // ═══════════════════════════════════════════════════
    // AFRIQUE DU NORD
    // ═══════════════════════════════════════════════════
    ['code' => 'DZ', 'name_fr' => 'Algerie', 'name_en' => 'Algeria', 'prefix' => '+213', 'digits' => 9, 'flag' => '🇩🇿'],
    ['code' => 'EG', 'name_fr' => 'Egypte', 'name_en' => 'Egypt', 'prefix' => '+20', 'digits' => 10, 'flag' => '🇪🇬'],
    ['code' => 'LY', 'name_fr' => 'Libye', 'name_en' => 'Libya', 'prefix' => '+218', 'digits' => 9, 'flag' => '🇱🇾'],
    ['code' => 'MA', 'name_fr' => 'Maroc', 'name_en' => 'Morocco', 'prefix' => '+212', 'digits' => 9, 'flag' => '🇲🇦'],
    ['code' => 'TN', 'name_fr' => 'Tunisie', 'name_en' => 'Tunisia', 'prefix' => '+216', 'digits' => 8, 'flag' => '🇹🇳'],

    // ═══════════════════════════════════════════════════
    // AFRIQUE AUSTRALE
    // ═══════════════════════════════════════════════════
    ['code' => 'AO', 'name_fr' => 'Angola', 'name_en' => 'Angola', 'prefix' => '+244', 'digits' => 9, 'flag' => '🇦🇴'],
    ['code' => 'BW', 'name_fr' => 'Botswana', 'name_en' => 'Botswana', 'prefix' => '+267', 'digits' => 8, 'flag' => '🇧🇼'],
    ['code' => 'LS', 'name_fr' => 'Lesotho', 'name_en' => 'Lesotho', 'prefix' => '+266', 'digits' => 8, 'flag' => '🇱🇸'],
    ['code' => 'NA', 'name_fr' => 'Namibie', 'name_en' => 'Namibia', 'prefix' => '+264', 'digits' => 9, 'flag' => '🇳🇦'],
    ['code' => 'ZA', 'name_fr' => 'Afrique du Sud', 'name_en' => 'South Africa', 'prefix' => '+27', 'digits' => 9, 'flag' => '🇿🇦'],
    ['code' => 'SZ', 'name_fr' => 'Eswatini', 'name_en' => 'Eswatini', 'prefix' => '+268', 'digits' => 8, 'flag' => '🇸🇿'],

    // ═══════════════════════════════════════════════════
    // EUROPE (principaux)
    // ═══════════════════════════════════════════════════
    ['code' => 'FR', 'name_fr' => 'France', 'name_en' => 'France', 'prefix' => '+33', 'digits' => 9, 'flag' => '🇫🇷'],
    ['code' => 'BE', 'name_fr' => 'Belgique', 'name_en' => 'Belgium', 'prefix' => '+32', 'digits' => 9, 'flag' => '🇧🇪'],
    ['code' => 'CH', 'name_fr' => 'Suisse', 'name_en' => 'Switzerland', 'prefix' => '+41', 'digits' => 9, 'flag' => '🇨🇭'],
    ['code' => 'DE', 'name_fr' => 'Allemagne', 'name_en' => 'Germany', 'prefix' => '+49', 'digits' => 11, 'flag' => '🇩🇪'],
    ['code' => 'ES', 'name_fr' => 'Espagne', 'name_en' => 'Spain', 'prefix' => '+34', 'digits' => 9, 'flag' => '🇪🇸'],
    ['code' => 'GB', 'name_fr' => 'Royaume-Uni', 'name_en' => 'United Kingdom', 'prefix' => '+44', 'digits' => 10, 'flag' => '🇬🇧'],
    ['code' => 'IT', 'name_fr' => 'Italie', 'name_en' => 'Italy', 'prefix' => '+39', 'digits' => 10, 'flag' => '🇮🇹'],
    ['code' => 'LU', 'name_fr' => 'Luxembourg', 'name_en' => 'Luxembourg', 'prefix' => '+352', 'digits' => 9, 'flag' => '🇱🇺'],
    ['code' => 'NL', 'name_fr' => 'Pays-Bas', 'name_en' => 'Netherlands', 'prefix' => '+31', 'digits' => 9, 'flag' => '🇳🇱'],
    ['code' => 'PT', 'name_fr' => 'Portugal', 'name_en' => 'Portugal', 'prefix' => '+351', 'digits' => 9, 'flag' => '🇵🇹'],

    // ═══════════════════════════════════════════════════
    // AMERIQUES (principaux)
    // ═══════════════════════════════════════════════════
    ['code' => 'US', 'name_fr' => 'Etats-Unis', 'name_en' => 'United States', 'prefix' => '+1', 'digits' => 10, 'flag' => '🇺🇸'],
    ['code' => 'CA', 'name_fr' => 'Canada', 'name_en' => 'Canada', 'prefix' => '+1', 'digits' => 10, 'flag' => '🇨🇦'],
    ['code' => 'BR', 'name_fr' => 'Bresil', 'name_en' => 'Brazil', 'prefix' => '+55', 'digits' => 11, 'flag' => '🇧🇷'],
    ['code' => 'HT', 'name_fr' => 'Haiti', 'name_en' => 'Haiti', 'prefix' => '+509', 'digits' => 8, 'flag' => '🇭🇹'],

    // ═══════════════════════════════════════════════════
    // ASIE / MOYEN-ORIENT (principaux)
    // ═══════════════════════════════════════════════════
    ['code' => 'CN', 'name_fr' => 'Chine', 'name_en' => 'China', 'prefix' => '+86', 'digits' => 11, 'flag' => '🇨🇳'],
    ['code' => 'IN', 'name_fr' => 'Inde', 'name_en' => 'India', 'prefix' => '+91', 'digits' => 10, 'flag' => '🇮🇳'],
    ['code' => 'JP', 'name_fr' => 'Japon', 'name_en' => 'Japan', 'prefix' => '+81', 'digits' => 10, 'flag' => '🇯🇵'],
    ['code' => 'LB', 'name_fr' => 'Liban', 'name_en' => 'Lebanon', 'prefix' => '+961', 'digits' => 8, 'flag' => '🇱🇧'],
    ['code' => 'SA', 'name_fr' => 'Arabie Saoudite', 'name_en' => 'Saudi Arabia', 'prefix' => '+966', 'digits' => 9, 'flag' => '🇸🇦'],
    ['code' => 'TR', 'name_fr' => 'Turquie', 'name_en' => 'Turkey', 'prefix' => '+90', 'digits' => 10, 'flag' => '🇹🇷'],
    ['code' => 'AE', 'name_fr' => 'Emirats Arabes Unis', 'name_en' => 'United Arab Emirates', 'prefix' => '+971', 'digits' => 9, 'flag' => '🇦🇪'],

    // ═══════════════════════════════════════════════════
    // OCEANIE
    // ═══════════════════════════════════════════════════
    ['code' => 'AU', 'name_fr' => 'Australie', 'name_en' => 'Australia', 'prefix' => '+61', 'digits' => 9, 'flag' => '🇦🇺'],

];
