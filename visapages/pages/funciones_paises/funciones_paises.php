<?php
/*Estas funciones se usan para determinar el país del usuario*/
/*El archivo IP2LOCATION... es la bbdd para determinar el país del usuario*/
/*Esta base de datos se obtuvo registrando una cuenta gratuita en: https://www.ip2location.com */
require '../vendor/autoload.php';
use IP2Location\Database;

/*Esta función obtiene la IP del usuario, como se está desarollando en el entorno local, hace falta usar
un servicio externo para obtener la IP pública*/
function obtenerIP() {
    return file_get_contents('https://api.ipify.org');
}

/*Esta función obtiene el páis de la IP usando bbdd citada anteriormente*/
/*Devuelve el código del país del usuario*/
function obtenerPais($ip) {
    $bbdd = './funciones_paises/IP2LOCATION-LITE-DB1.IPV6.BIN';

    if (!file_exists($bbdd)) {
        die("Base de datos de IP2Location no encontrada: $bbdd");
    }

    try {
        $db = new Database($bbdd, Database::FILE_IO);

        $records = $db->lookup($ip, Database::ALL);

        if (!$records || !isset($records['countryCode'])) {
            echo "No hay datos para la IP: " . $ip . "\n";
        } else {
            return $records['countryCode'];
        }

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
    return null;
}

/*Ya que la función anterior nos devuelve el código del país (por ejemplo, ES para España)
usamos esta función para convertir el código al nombre país*/
function obtenerNombrePais($codigo_pais) {
    $paises = [
        'AF' => 'Afganistán',
        'AX' => 'Islas Åland',
        'AL' => 'Albania',
        'DZ' => 'Argelia',
        'AS' => 'Samoa Americana',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguila',
        'AG' => 'Antigua y Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaiyán',
        'BS' => 'Bahamas',
        'BH' => 'Baréin',
        'BB' => 'Barbados',
        'BY' => 'Bielorrusia',
        'BE' => 'Bélgica',
        'BZ' => 'Belice',
        'BJ' => 'Benín',
        'BM' => 'Bermudas',
        'BT' => 'Bután',
        'BQ' => 'Bonaire, San Eustaquio y Saba',
        'BA' => 'Bosnia y Herzegovina',
        'BN' => 'Brunéi',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'CV' => 'Cabo Verde',
        'KH' => 'Camboya',
        'CM' => 'Camerún',
        'CA' => 'Canadá',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Isla de Navidad',
        'CC' => 'Islas Cocos',
        'CO' => 'Colombia',
        'KM' => 'Comoras',
        'CG' => 'Congo',
        'CK' => 'Islas Cook',
        'CR' => 'Costa Rica',
        'HR' => 'Croacia',
        'CU' => 'Cuba',
        'CW' => 'Curazao',
        'CY' => 'Chipre',
        'CZ' => 'Chequia',
        'DK' => 'Dinamarca',
        'DJ' => 'Yibuti',
        'DM' => 'Dominica',
        'EC' => 'Ecuador',
        'EG' => 'Egipto',
        'SV' => 'El Salvador',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'SZ' => 'Eswatini',
        'FJ' => 'Fiyi',
        'FI' => 'Finlandia',
        'FR' => 'Francia',
        'PF' => 'Polinesia Francesa',
        'GA' => 'Gabón',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Alemania',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Grecia',
        'GL' => 'Groenlandia',
        'GD' => 'Granada',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GW' => 'Guinea-Bisáu',
        'HT' => 'Haití',
        'VA' => 'Santa Sede',
        'HK' => 'Hong Kong',
        'HU' => 'Hungría',
        'IS' => 'Islandia',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Irán',
        'IQ' => 'Irak',
        'IE' => 'Irlanda',
        'IM' => 'Isla de Man',
        'IT' => 'Italia',
        'JP' => 'Japón',
        'JE' => 'Jersey',
        'JO' => 'Jordania',
        'KE' => 'Kenia',
        'KP' => 'Corea del Norte',
        'KR' => 'Corea del Sur',
        'KW' => 'Kuwait',
        'KG' => 'Kirguistán',
        'LA' => 'Laos',
        'LV' => 'Letonia',
        'LB' => 'Líbano',
        'LS' => 'Lesoto',
        'LR' => 'Liberia',
        'LI' => 'Liechtenstein',
        'LT' => 'Lituania',
        'LU' => 'Luxemburgo',
        'MO' => 'Macao',
        'MG' => 'Madagascar',
        'MW' => 'Malaui',
        'MY' => 'Malasia',
        'MQ' => 'Martinica',
        'MR' => 'Mauritania',
        'YT' => 'Mayotte',
        'MX' => 'México',
        'MC' => 'Mónaco',
        'ME' => 'Montenegro',
        'MZ' => 'Mozambique',
        'MM' => 'Birmania',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NL' => 'Países Bajos',
        'NZ' => 'Nueva Zelanda',
        'NI' => 'Nicaragua',
        'NE' => 'Níger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Isla Norfolk',
        'MP' => 'Islas Marianas del Norte',
        'NO' => 'Noruega',
        'OM' => 'Omán',
        'PK' => 'Pakistán',
        'PW' => 'Palaos',
        'PY' => 'Paraguay',
        'PE' => 'Perú',
        'PL' => 'Polonia',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Catar',
        'RE' => 'Reunión',
        'RO' => 'Rumanía',
        'RW' => 'Ruanda',
        'BL' => 'San Bartolomé',
        'MF' => 'San Martín',
        'PM' => 'San Pedro y Miquelón',
        'VC' => 'San Vicente y las Granadinas',
        'ST' => 'Santo Tomé y Príncipe',
        'SA' => 'Arabia Saudita',
        'SN' => 'Senegal',
        'SC' => 'Seychelles',
        'SG' => 'Singapur',
        'SK' => 'Eslovaquia',
        'ZA' => 'Sudáfrica',
        'ES' => 'España',
        'LK' => 'Sri Lanka',
        'SJ' => 'Svalbard y Jan Mayen',
        'SE' => 'Suecia',
        'CH' => 'Suiza',
        'SY' => 'Siria',
        'TW' => 'Taiwán',
        'TH' => 'Tailandia',
        'TG' => 'Togo',
        'TO' => 'Tonga',
        'TN' => 'Túnez',
        'TR' => 'Turquía',
        'TC' => 'Islas Turcas y Caicos',
        'TV' => 'Tuvalu',
        'UA' => 'Ucrania',
        'AE' => 'Emiratos Árabes Unidos',
        'GB' => 'Reino Unido',
        'US' => 'Estados Unidos',
        'UZ' => 'Uzbekistán',
        'VU' => 'Vanuatu',
        'VG' => 'Islas Vírgenes Británicas',
        'VI' => 'Islas Vírgenes de los Estados Unidos',
        'WF' => 'Wallis y Futuna',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabue'
    ];

    /*Si el código, por el motivo que sea, no sea encuentra, se duvuelve país inválido*/
    return $paises[$codigo_pais] ?? 'País no válido';
}

