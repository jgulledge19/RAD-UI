<?php

/**
 * This is a common list of 
 */
 
function getCommonSelectOptions($key)
{
    $options = array(
        'countries'=> array(
            "United States of America" => "United States of America",
            "United Kingdom" => "United Kingdom",
            "Afghanistan" => "Afghanistan",
            "Albania" => "Albania",
            "Algeria" => "Algeria",
            "American Samoa" => "American Samoa",
            "Andorra" => "Andorra",
            "Angola" => "Angola",
            "Anguilla" => "Anguilla",
            "Antarctica" => "Antarctica",
            "Antigua And Barbuda" => "Antigua And Barbuda",
            "Argentina" => "Argentina",
            "Armenia" => "Armenia",
            "Aruba" => "Aruba",
            "Australia" => "Australia",
            "Austria" => "Austria",
            "Azerbaijan" => "Azerbaijan",
            "Bahamas" => "Bahamas",
            "Bahrain" => "Bahrain",
            "Bangladesh" => "Bangladesh",
            "Barbados" => "Barbados",
            "Belarus" => "Belarus",
            "Belgium" => "Belgium",
            "Belize" => "Belize",
            "Benin" => "Benin",
            "Bermuda" => "Bermuda",
            "Bhutan" => "Bhutan",
            "Bolivia" => "Bolivia",
            "Bosnia And Herzegowina" => "Bosnia And Herzegowina",
            "Botswana" => "Botswana",
            "Bouvet Island" => "Bouvet Island",
            "Brazil" => "Brazil",
            "British Indian Ocean Territory" => "British Indian Ocean Territory",
            "Brunei Darussalam" => "Brunei Darussalam",
            "Bulgaria" => "Bulgaria",
            "Burkina Faso" => "Burkina Faso",
            "Burundi" => "Burundi",
            "Cambodia" => "Cambodia",
            "Cameroon" => "Cameroon",
            "Canada" => "Canada",
            "Cape Verde" => "Cape Verde",
            "Cayman Islands" => "Cayman Islands",
            "Central African Republic" => "Central African Republic",
            "Chad" => "Chad",
            "Chile" => "Chile",
            "China" => "China",
            "Christmas Island" => "Christmas Island",
            "Cocos (Keeling) Islands" => "Cocos (Keeling) Islands",
            "Colombia" => "Colombia",
            "Comoros" => "Comoros",
            "Congo" => "Congo",
            "Congo, The Democratic Republic Of The" => "Congo, The Democratic Republic Of The",
            "Cook Islands" => "Cook Islands",
            "Costa Rica" => "Costa Rica",
            "Cote D'Ivoire" => "Cote D'Ivoire",
            "Croatia (Local Name: Hrvatska)" => "Croatia (Local Name: Hrvatska)",
            "Cuba" => "Cuba",
            "Cyprus" => "Cyprus",
            "Czech Republic" => "Czech Republic",
            "Denmark" => "Denmark",
            "Djibouti" => "Djibouti",
            "Dominica" => "Dominica",
            "Dominican Republic" => "Dominican Republic",
            "East Timor" => "East Timor",
            "Ecuador" => "Ecuador",
            "Egypt" => "Egypt",
            "El Salvador" => "El Salvador",
            "Equatorial Guinea" => "Equatorial Guinea",
            "Eritrea" => "Eritrea",
            "Estonia" => "Estonia",
            "Ethiopia" => "Ethiopia",
            "Falkland Islands (Malvinas)" => "Falkland Islands (Malvinas)",
            "Faroe Islands" => "Faroe Islands",
            "Fiji" => "Fiji",
            "Finland" => "Finland",
            "France" => "France",
            "France, Metropolitan" => "France, Metropolitan",
            "French Guiana" => "French Guiana",
            "French Polynesia" => "French Polynesia",
            "French Southern Territories" => "French Southern Territories",
            "Gabon" => "Gabon",
            "Gambia" => "Gambia",
            "Georgia" => "Georgia",
            "Germany" => "Germany",
            "Ghana" => "Ghana",
            "Gibraltar" => "Gibraltar",
            "Greece" => "Greece",
            "Greenland" => "Greenland",
            "Grenada" => "Grenada",
            "Guadeloupe" => "Guadeloupe",
            "Guam" => "Guam",
            "Guatemala" => "Guatemala",
            "Guinea" => "Guinea",
            "Guinea-Bissau" => "Guinea-Bissau",
            "Guyana" => "Guyana",
            "Haiti" => "Haiti",
            "Heard And Mc Donald Islands" => "Heard And Mc Donald Islands",
            "Holy See (Vatican City State)" => "Holy See (Vatican City State)",
            "Honduras" => "Honduras",
            "Hong Kong" => "Hong Kong",
            "Hungary" => "Hungary",
            "Iceland" => "Iceland",
            "India" => "India",
            "Indonesia" => "Indonesia",
            "Iran (Islamic Republic Of)" => "Iran (Islamic Republic Of)",
            "Iraq" => "Iraq",
            "Ireland" => "Ireland",
            "Israel" => "Israel",
            "Italy" => "Italy",
            "Jamaica" => "Jamaica",
            "Japan" => "Japan",
            "Jordan" => "Jordan",
            "Kazakhstan" => "Kazakhstan",
            "Kenya" => "Kenya",
            "Kiribati" => "Kiribati",
            "Korea, Democratic People's Republic Of" => "Korea, Democratic People's Republic Of",
            "Korea, Republic Of" => "Korea, Republic Of",
            "Kuwait" => "Kuwait",
            "Kyrgyzstan" => "Kyrgyzstan",
            "Lao People's Democratic Republic" => "Lao People's Democratic Republic",
            "Latvia" => "Latvia",
            "Lebanon" => "Lebanon",
            "Lesotho" => "Lesotho",
            "Liberia" => "Liberia",
            "Libyan Arab Jamahiriya" => "Libyan Arab Jamahiriya",
            "Liechtenstein" => "Liechtenstein",
            "Lithuania" => "Lithuania",
            "Luxembourg" => "Luxembourg",
            "Macau" => "Macau",
            "Macedonia, Former Yugoslav Republic Of" => "Macedonia, Former Yugoslav Republic Of",
            "Madagascar" => "Madagascar",
            "Malawi" => "Malawi",
            "Malaysia" => "Malaysia",
            "Maldives" => "Maldives",
            "Mali" => "Mali",
            "Malta" => "Malta",
            "Marshall Islands" => "Marshall Islands",
            "Martinique" => "Martinique",
            "Mauritania" => "Mauritania",
            "Mauritius" => "Mauritius",
            "Mayotte" => "Mayotte",
            "Mexico" => "Mexico",
            "Micronesia, Federated States Of" => "Micronesia, Federated States Of",
            "Moldova, Republic Of" => "Moldova, Republic Of",
            "Monaco" => "Monaco",
            "Mongolia" => "Mongolia",
            "Montserrat" => "Montserrat",
            "Morocco" => "Morocco",
            "Mozambique" => "Mozambique",
            "Myanmar" => "Myanmar",
            "Namibia" => "Namibia",
            "Nauru" => "Nauru",
            "Nepal" => "Nepal",
            "Netherlands" => "Netherlands",
            "Netherlands Antilles" => "Netherlands Antilles",
            "New Caledonia" => "New Caledonia",
            "New Zealand" => "New Zealand",
            "Nicaragua" => "Nicaragua",
            "Niger" => "Niger",
            "Nigeria" => "Nigeria",
            "Niue" => "Niue",
            "Norfolk Island" => "Norfolk Island",
            "Northern Mariana Islands" => "Northern Mariana Islands",
            "Norway" => "Norway",
            "Oman" => "Oman",
            "Pakistan" => "Pakistan",
            "Palau" => "Palau",
            "Panama" => "Panama",
            "Papua New Guinea" => "Papua New Guinea",
            "Paraguay" => "Paraguay",
            "Peru" => "Peru",
            "Philippines" => "Philippines",
            "Pitcairn" => "Pitcairn",
            "Poland" => "Poland",
            "Portugal" => "Portugal",
            "Puerto Rico" => "Puerto Rico",
            "Qatar" => "Qatar",
            "Reunion" => "Reunion",
            "Romania" => "Romania",
            "Russian Federation" => "Russian Federation",
            "Rwanda" => "Rwanda",
            "Saint Kitts And Nevis" => "Saint Kitts And Nevis",
            "Saint Lucia" => "Saint Lucia",
            "Saint Vincent And The Grenadines" => "Saint Vincent And The Grenadines",
            "Samoa" => "Samoa",
            "San Marino" => "San Marino",
            "Sao Tome And Principe" => "Sao Tome And Principe",
            "Saudi Arabia" => "Saudi Arabia",
            "Senegal" => "Senegal",
            "Seychelles" => "Seychelles",
            "Sierra Leone" => "Sierra Leone",
            "Singapore" => "Singapore",
            "Slovakia (Slovak Republic)" => "Slovakia (Slovak Republic)",
            "Slovenia" => "Slovenia",
            "Solomon Islands" => "Solomon Islands",
            "Somalia" => "Somalia",
            "South Africa" => "South Africa",
            "South Georgia, South Sandwich Islands" => "South Georgia, South Sandwich Islands",
            "Spain" => "Spain",
            "Sri Lanka" => "Sri Lanka",
            "St. Helena" => "St. Helena",
            "St. Pierre And Miquelon" => "St. Pierre And Miquelon",
            "Sudan" => "Sudan",
            "Suriname" => "Suriname",
            "Svalbard And Jan Mayen Islands" => "Svalbard And Jan Mayen Islands",
            "Swaziland" => "Swaziland",
            "Sweden" => "Sweden",
            "Switzerland" => "Switzerland",
            "Syrian Arab Republic" => "Syrian Arab Republic",
            "Taiwan" => "Taiwan",
            "Tajikistan" => "Tajikistan",
            "Tanzania, United Republic Of" => "Tanzania, United Republic Of",
            "Thailand" => "Thailand",
            "Togo" => "Togo",
            "Tokelau" => "Tokelau",
            "Tonga" => "Tonga",
            "Trinidad And Tobago" => "Trinidad And Tobago",
            "Tunisia" => "Tunisia",
            "Turkey" => "Turkey",
            "Turkmenistan" => "Turkmenistan",
            "Turks And Caicos Islands" => "Turks And Caicos Islands",
            "Tuvalu" => "Tuvalu",
            "Uganda" => "Uganda",
            "Ukraine" => "Ukraine",
            "United Arab Emirates" => "United Arab Emirates",
            "United States of America Minor Outlying Islands" => "United States of America Minor Outlying Islands",
            "Uruguay" => "Uruguay",
            "Uzbekistan" => "Uzbekistan",
            "Vanuatu" => "Vanuatu",
            "Venezuela" => "Venezuela",
            "Viet Nam" => "Viet Nam",
            "Virgin Islands (British)" => "Virgin Islands (British)",
            "Virgin Islands (U.S.)" => "Virgin Islands (U.S.)",
            "Wallis And Futuna Islands" => "Wallis And Futuna Islands",
            "Western Sahara" => "Western Sahara",
            "Yemen" => "Yemen",
            "Yugoslavia" => "Yugoslavia",
            "Zambia" => "Zambia",
            "Zimbabwe" => "Zimbabwe"
        ),
        'countryCodes' => array( 
            'UNITED STATES OF AMERICA' => 'USA', 
            'ARUBA' => 'ABW', 
            'AFGHANISTAN' => 'AFG', 
            'ANGOLA' => 'AGO', 
            'ANGUILLA' => 'AIA', 
            'ALBANIA' => 'ALB', 
            'ANDORRA' => 'AND', 
            'NETHERLANDS ANTILLES' => 'ANT', 
            'UNITED ARAB EMIRATES' => 'ARE', 
            'ARGENTINA' => 'ARG', 
            'ARMENIA' => 'ARM', 
            'AMERICAN SAMOA' => 'ASM', 
            'ANTARCTICA' => 'ATA', 
            'FRENCH SOUTHERN TERRITORI' => 'ATF', 
            'ANTIGUA AND BARBUDA' => 'ATG', 
            'AUSTRALIA' => 'AUS', 
            'AUSTRIA' => 'AUT', 
            'AZERBAIJAN' => 'AZE', 
            'BAHAMAS' => 'BAH', 
            'BURUNDI' => 'BDI', 
            'BELGIUM' => 'BEL', 
            'BENIN' => 'BEN', 
            'BURKINA FASO' => 'BFA', 
            'BANGLADESH' => 'BGD', 
            'BAHRAIN' => 'BHR', 
            'BOSNIA HERZEGOVINA' => 'BIH', 
            'BELARUS' => 'BLR', 
            'BELIZE' => 'BLZ', 
            'BERMUDA' => 'BMU', 
            'BOLIVIA' => 'BOL', 
            'BRAZIL' => 'BRA', 
            'BARBADOS' => 'BRB', 
            'BRUNEI DARUSSALAM' => 'BRN', 
            'BHUTAN' => 'BTN', 
            'BULGARIA' => 'BUL', 
            'BOUVET ISLAND' => 'BVT', 
            'BOTSWANA' => 'BWA', 
            'CENTRAL AFRICAN REPUBLIC' => 'CAF', 
            'CANADA' => 'CAN', 
            'COCOS (KEELING) ISLANDS' => 'CCK', 
            'SWITZERLAND' => 'CHE', 
            'CHILE' => 'CHL', 
            'CHINA' => 'CHN', 
            'COTE D&rsquo;IVORIE' => 'CIV', 
            'CAMEROON' => 'CMR', 
            'CONGO  THE DEMOCRATIC REP' => 'COD', 
            'CONGO' => 'COG', 
            'COOK ISLANDS' => 'COK', 
            'COLOMBIA' => 'COL', 
            'COMOROS' => 'COM', 
            'CAPE VERDE' => 'CPV', 
            'COSTA RICA' => 'CRI', 
            'CUBA' => 'CUB', 
            'CHRISTMAS ISLAND' => 'CXR', 
            'CAYMAN ISLANDS' => 'CYM', 
            'CYPRUS' => 'CYP', 
            'CZECH REPUBLIC' => 'CZE', 
            'GERMANY' => 'DEU', 
            'DJIBOUTI' => 'DJI', 
            'DOMINICA' => 'DMA', 
            'DENMARK' => 'DNK', 
            'DOMINICAN REPUBLIC' => 'DOM', 
            'ALGERIA' => 'DZA', 
            'ECUADOR' => 'ECU', 
            'EGYPT' => 'EGY', 
            'ENGLAND' => 'ENG', 
            'ERITREA' => 'ERI', 
            'WESTERN SAHARA' => 'ESH', 
            'SPAIN' => 'ESP', 
            'ESTONIA' => 'EST', 
            'ETHIOPIA' => 'ETH', 
            'FINLAND' => 'FIN', 
            'FIJI' => 'FJI', 
            'FALKLAND' => 'FLK', 
            'FRANCE' => 'FRA', 
            'FAROE ISLANDS' => 'FRO', 
            'MICRONESIA  FEDERATED STA' => 'FSM', 
            'GABON' => 'GAB', 
            'UNITED KINGDOM' => 'GBR', 
            'GEORGIA' => 'GEO', 
            'GHANA' => 'GHA', 
            'GIBRALTAR' => 'GIB', 
            'GUINEA West Africa' => 'GIN', 
            'GUADELOUPE' => 'GLP', 
            'GAMBIA' => 'GMB', 
            'GUINEA-BISSAU' => 'GNB', 
            'EQUATORIAL GUINEA' => 'GNQ', 
            'GRENADA' => 'GRD', 
            'GREECE' => 'GRE', 
            'GREENLAND' => 'GRL', 
            'GUATEMALA' => 'GTM', 
            'FRENCH GUIANA' => 'GUF', 
            'GUAM' => 'GUM', 
            'GUYANA' => 'GUY', 
            'HONG KONG' => 'HKG', 
            'HEARD AND MC DONALD ISLAN' => 'HMD', 
            'HONDURAS  Central Americ' => 'HND', 
            'CROATIA' => 'HRV', 
            'HAITI' => 'HTI', 
            'HUNGARY' => 'HUN', 
            'INDONESIA' => 'IDN', 
            'INDIA' => 'IND', 
            'BRITISH INDIAN OCEAN TERR' => 'IOT', 
            'IRELAND' => 'IRL', 
            'IRAN (ISLAMIC REPUBLIC)' => 'IRN', 
            'IRAQ' => 'IRQ', 
            'ICELAND' => 'ISL', 
            'ISRAEL' => 'ISR', 
            'ITALY' => 'ITA', 
            'JAMAICA' => 'JAM', 
            'JORDAN' => 'JOR', 
            'JAPAN' => 'JPN', 
            'KAZAKHSTAN' => 'KAZ', 
            'KENYA  East Africa' => 'KEN', 
            'KYRGYZSTAN' => 'KGZ', 
            'CAMBODIA' => 'KHM', 
            'KIRIBATI' => 'KIR', 
            'SAINT KITTS AND NEVIS' => 'KNA', 
            'KOREA  REPUBLIC OF' => 'KOR', 
            'KUWAIT' => 'KWT', 
            'LAOS' => 'LAO', 
            'LEBANON' => 'LBN', 
            'LIBERIA' => 'LBR', 
            'LIBYAN ARAB JAMAHIRIYA' => 'LBY', 
            'SAINT LUCIA' => 'LCA', 
            'LIECHTENSTEIN' => 'LIE', 
            'SRI LANKA' => 'LKA', 
            'LESOTHO' => 'LSO', 
            'LITHUANIA' => 'LTU', 
            'LUXEMBOURG' => 'LUX', 
            'LATVIA' => 'LVA', 
            'MACAU' => 'MAC', 
            'MOROCCO' => 'MAR', 
            'MONACO' => 'MCO', 
            'MADAGASCAR' => 'MDG', 
            'MALDIVES' => 'MDV', 
            'MEXICO' => 'MEX', 
            'MARSHALL ISLANDS' => 'MHL', 
            'MACEDONIA  THE FORMER YUG' => 'MKD', 
            'MALI' => 'MLI', 
            'MALTA' => 'MLT', 
            'MYANMAR' => 'MMR', 
            'MONGOLIA' => 'MNG', 
            'NORTHERN MARIANA ISLANDS' => 'MNP', 
            'MOLDOVA' => 'MOL', 
            'MOZAMBIQUE' => 'MOZ', 
            'MAURITANIA' => 'MRT', 
            'MONTSERRAT' => 'MSR', 
            'MARTINIQUE' => 'MTQ', 
            'MAURITIUS' => 'MUS', 
            'MALAWI' => 'MWI', 
            'MALAYSIA' => 'MYS', 
            'MAYOTTE' => 'MYT', 
            'NAMIBIA' => 'NAM', 
            'NEW CALEDONIA' => 'NCL', 
            'NIGER' => 'NER', 
            'NORFOLK ISLAND' => 'NFK', 
            'NEW GUINEA' => 'NG', 
            'NIGERIA  West Africa' => 'NGA', 
            'NICARAGUA' => 'NIC', 
            'NIUE' => 'NIU', 
            'NETHERLANDS' => 'NLD', 
            'NORWAY' => 'NOR', 
            'NEPAL' => 'NPL', 
            'NAURU' => 'NRU', 
            'NEW ZEALAND' => 'NZL', 
            'OMAN' => 'OMN', 
            'PAKISTAN' => 'PAK', 
            'PANAMA' => 'PAN', 
            'PITCAIRN' => 'PCN', 
            'PERU  South America' => 'PER', 
            'PHILIPPINES' => 'PHL', 
            'PALAU' => 'PLW', 
            'PAPUA NEW GUINEA' => 'PNG', 
            'POLAND' => 'POL', 
            'PUERTO RICO' => 'PRI', 
            'KOREA  DEMOCRATIC PEOPLE' => 'PRK', 
            'PORTUGAL' => 'PRT', 
            'PARAGUAY' => 'PRY', 
            'FRENCH POLYNESIA' => 'PYF', 
            'QATAR' => 'QAT', 
            'REUNION' => 'REU', 
            'ROMANIA' => 'ROM', 
            'RUSSIAN FEDERATION' => 'RUS', 
            'RWANDA' => 'RWA', 
            'SAUDI ARABIA' => 'SAU', 
            'SCOTLAND' => 'SCO', 
            'SUDAN' => 'SDN', 
            'SENEGAL' => 'SEN', 
            'REPUBLIC OF SINGAPORE' => 'SGP', 
            'SOUTH GEORGIA AND THE SOU' => 'SGS', 
            'ST. HELENA' => 'SHN', 
            'SVALBARD AND JAN MAYEN IS' => 'SJM', 
            'SOLOMON ISLANDS' => 'SLB', 
            'SIERRA LEONE' => 'SLE', 
            'EL SALVADOR' => 'SLV', 
            'SAN MARINO' => 'SMR', 
            'SOMALIA' => 'SOM', 
            'ST. PIERRE AND MIQUELON' => 'SPM', 
            'SAO TOME AND PRINCIPE' => 'STP', 
            'SURINAME' => 'SUR', 
            'SLOVAKIA (SLOVAK REPUBLIC' => 'SVK', 
            'SLOVENIA' => 'SVN', 
            'SWEDEN' => 'SWE', 
            'SWASILAND' => 'SWZ', 
            'SEYCHELLES' => 'SYC', 
            'SYRIAN ARAB REPUBLIC' => 'SYR', 
            'TAIWAN' => 'TAI', 
            'TURKS AND CAICOS ISLANDS' => 'TCA', 
            'CHAD' => 'TCD', 
            'TOGO' => 'TGO', 
            'THAILAND' => 'THA', 
            'TAJIKISTAN' => 'TJK', 
            'TOKELAU' => 'TKL', 
            'TURKMENISTAN' => 'TKM', 
            'EAST TIMOR' => 'TMP', 
            'TONGA' => 'TON', 
            'TRINIDAD AND TOBAGO' => 'TTO', 
            'TUNISIA' => 'TUN', 
            'TURKEY' => 'TUR', 
            'TUVALU' => 'TUV', 
            'TAIWAN  PROVINCE OF CHINA' => 'TWN', 
            'TANZANIA' => 'TZA', 
            'UGANDA' => 'UGA', 
            'UKRAINE' => 'UKR', 
            'UNITED STATES MINOR OUTLY' => 'UMI', 
            'URUAGUAY' => 'URU', 
            'UZBEKISTAN' => 'UZB', 
            'VATICAN CITY' => 'VAT', 
            'SAINT VINCENT AND THE GRE' => 'VCT', 
            'VENEZUELA' => 'VEN', 
            'VIRGIN ISLANDS (BRITISH)' => 'VGB', 
            'VIRGIN ISLANDS (U.S.)' => 'VIR', 
            'VIETNAM' => 'VNM', 
            'VANUATU' => 'VUT', 
            'WEST AFRICA' => 'WAF', 
            'WALLIS AND FUTUNA ISLANDS' => 'WLF', 
            'SAMOA' => 'WSM', 
            'YEMEN' => 'YEM', 
            'YUGOSLAVIA' => 'YUG', 
            'REPUBLIC OF SOUTH AFRICA' => 'ZAF', 
            'ZAIRE' => 'ZAI', 
            'ZAMBIA' => 'ZMB', 
            'ZIMBABWE' => 'ZWE'
        ),
        'stateTerritoryCodes' => array(
            'Select ' => '',
            'Foreign Address' => '--', 
            'Military Location' => 'AA', 
            //'Alberta' => 'AB', 
            //'Military Location' => 'AE', 
            'Alaska' => 'AK', 
            'Alabama' => 'AL', 
            //'Military Location' => 'AP', 
            'Arkansas' => 'AR', 
            'American Samoa' => 'AS', 
            'Arizona' => 'AZ', 
            //'British Columbia' => 'BC', 
            'California' => 'CA', 
            'Canada' => 'CN', 
            'Colorado' => 'CO', 
            'Connecticut' => 'CT', 
            //'Canal Zone' => 'CZ', 
            'District of Columbia' => 'DC', 
            'Delaware' => 'DE', 
            //'Foreign Country' => 'FC', 
            'Florida' => 'FL', 
            //'Federated Micronesia' => 'FM', 
            'Georgia' => 'GA', 
            //'Guam' => 'GU', 
            'Hawaii' => 'HI', 
            'Iowa' => 'IA', 
            'Idaho' => 'ID', 
            'Illinois' => 'IL', 
            'Indiana' => 'IN', 
            'Kansas' => 'KS', 
            'Kentucky' => 'KY', 
            'Louisiana' => 'LA', 
            //'Labrador' => 'LB', 
            'Massachusetts' => 'MA', 
            //'Manitoba' => 'MB', 
            //'Micronesia' => 'MC', 
            'Maryland' => 'MD', 
            'Maine' => 'ME', 
            //'Marshall Islands' => 'MH', 
            'Michigan' => 'MI', 
            'Minnesota' => 'MN', 
            'Missouri' => 'MO', 
            'Northern Mariana' => 'MP', 
            'Mississippi' => 'MS', 
            'Montana' => 'MT', 
            'Mexico' => 'MX', 
            //'New Brunswick' => 'NB', 
            'North Carolina' => 'NC', 
            'North Dakota' => 'ND', 
            'Nebraska' => 'NE', 
            'Newfoundland' => 'NF', 
            'New Hampshire' => 'NH', 
            'New Jersey' => 'NJ', 
            //'Newfoundland/Labrado' => 'NL', 
            'New Mexico' => 'NM', 
            //'Nova Scotia' => 'NS', 
            //'Northwest Territorie' => 'NT', 
            //'Nunavut' => 'NU', 
            'Nevada' => 'NV', 
            'New York' => 'NY', 
            'Ohio' => 'OH', 
            'Oklahoma' => 'OK', 
            //'Ontario' => 'ON', 
            'Oregon' => 'OR', 
            'Pennsylvania' => 'PA', 
            //'Prince Edward Island' => 'PE', 
            //'Quebec' => 'PQ', 
            'Puerto Rico' => 'PR', 
            'Palau' => 'PW', 
            //'Quebec' => 'QC', 
            'Rhode Island' => 'RI', 
            'South Carolina' => 'SC', 
            'South Dakota' => 'SD', 
            //'Saskatchewan' => 'SK', 
            'Tennessee' => 'TN', 
            'Texas' => 'TX', 
            'Utah' => 'UT', 
            'Virginia' => 'VA', 
            'Virgin Islands' => 'VI', 
            'Vermont' => 'VT', 
            'Washington' => 'WA', 
            'Wisconsin' => 'WI', 
            'West Virginia' => 'WV', 
            'Wyoming' => 'WY', 
            //'Yukon Territories' => 'YK', 
            //'Yukon Territories' => 'YT'
         ),
        'states' => array(),
        'stateCodes' => array(
                    // name(disply) => value
                    'AL'=>"Alabama",  
                    'AK'=>"Alaska",  
                    'AZ'=>"Arizona",  
                    'AR'=>"Arkansas",  
                    'CA'=>"California",  
                    'CO'=>"Colorado",  
                    'CT'=>"Connecticut",  
                    'DE'=>"Delaware",  
                    'DC'=>"District Of Columbia",  
                    'FL'=>"Florida",  
                    'GA'=>"Georgia",  
                    'HI'=>"Hawaii",  
                    'ID'=>"Idaho",  
                    'IL'=>"Illinois",  
                    'IN'=>"Indiana",  
                    'IA'=>"Iowa",  
                    'KS'=>"Kansas",  
                    'KY'=>"Kentucky",  
                    'LA'=>"Louisiana",  
                    'ME'=>"Maine",  
                    'MD'=>"Maryland",  
                    'MA'=>"Massachusetts",  
                    'MI'=>"Michigan",  
                    'MN'=>"Minnesota",  
                    'MS'=>"Mississippi",  
                    'MO'=>"Missouri",  
                    'MT'=>"Montana",
                    'NE'=>"Nebraska",
                    'NV'=>"Nevada",
                    'NH'=>"New Hampshire",
                    'NJ'=>"New Jersey",
                    'NM'=>"New Mexico",
                    'NY'=>"New York",
                    'NC'=>"North Carolina",
                    'ND'=>"North Dakota",
                    'OH'=>"Ohio",  
                    'OK'=>"Oklahoma",  
                    'OR'=>"Oregon",  
                    'PA'=>"Pennsylvania",  
                    'RI'=>"Rhode Island",  
                    'SC'=>"South Carolina",  
                    'SD'=>"South Dakota",
                    'TN'=>"Tennessee",  
                    'TX'=>"Texas",  
                    'UT'=>"Utah",  
                    'VT'=>"Vermont",  
                    'VA'=>"Virginia",  
                    'WA'=>"Washington",  
                    'WV'=>"West Virginia",  
                    'WI'=>"Wisconsin",  
                    'WY'=>"Wyoming"
                )
    );
    $year_array = array();
    $c_year = date("Y");
    $c_month = date("n");
    if( $c_month > 8 ){
        ++$c_year;
    }
    $stop = $c_year + 3;
    for( $x=$c_year; $x <= $stop; $x++ ){
        $year_array[$x] = $x;
    }
    $options['enrollYears'] = $year_array;
    
    if ( isset($options[$key]) ) {
        return $options[$key];
    }
    return array();
}