<?php

/**
 * Konstanter (bootstrap)
 * @author Niklas Dougherty
 */

declare(strict_types=1);

define('VERSION', '0.8.0.17');
define('VERSIONSDATUM', '2025-06-19');

/**
 * Välj felrapportering.
 */
define('FELRAPPORTERING', true);

define('ROT', realpath(__DIR__ . '/..'));

/**
 * Bestäm vilket dataset som ska användas.
 * Test används automatiskt av PHPUnit.
 * Dist är defaultkonfiguration.
 * Skarp bör kopieras från dist och byggas ut.
 */
define('PREFIX', match (true) {
	is_dir(ROT . '/_data/test') => '/_data/test',
	is_dir(ROT . '/_data/skarp') => '/_data/skarp',
	default => '/_data/dist'
});

define('BAS', ROT . PREFIX);
define('DB', BAS . '/db');

define('GRAF', BAS . '/graf');
define('HTML_GRAF', PREFIX . '/graf');

/**
 * Relativa.
 */
define('GENERERADE', '/genererade');
define('DISTRIBUTION', '/distribution');
define('SPELADE', '/spelade');
define('VINSTSPRIDNING', '/vinstspridning');

/**
 * Rotbaserade.
 */
define('FUNKTIONER', ROT . '/funktioner');
define('KLASSER', ROT . '/Klasser');
define('EGENSKAPER', ROT . '/Egenskaper');
define('MODULER', ROT . '/Moduler');
define('KODER', ROT . '/Koder');
define('R1', KODER . '/R1');
define('R2', KODER . '/R2');
define('INKLUDERA', ROT . '/Inkludera');
define('PARALLELLISERING', ROT . '/Parallellisering');
define('BACKUP', ROT . '/../backup');

/**
 * Ajax.
 */
define('AJAX', ROT . '/Ajax');
define('JSONFIL', AJAX . '/_tips_json.txt');

/**
 * API
 */
define('USERAGENT', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36');
define('SVENSKA_SPEL_API_URL', 'https://api.www.svenskaspel.se/external/1/draw/');

/**
 * Konstanter.
 */
define('MATCHANTAL', 13);
define('MATCHRYMD', pow(3, MATCHANTAL));
define('MIN_RADER', 10);
define('MAX_RADER', 10000);
define('MAX_RADER_TOTALT', 20000);
define('MAXVINST', 20000000);
define('TECKENRYMD', [0, 1, 2]);
define('TRÅDMÄNGD', [1, 3, 9, 27, 81]);
define('KUBER', array_map(fn (int $n): int => 3 ** $n, range(MATCHANTAL - 1, 0))); // 3^(13-(k+1))
define('USÖMNTID', 10000);
define('MAXTID', 10);

define('TOM_ODDSVEKTOR', [0.0, 0.0, 0.0]);
define('TOM_STRÄNGVEKTOR', ['', '', '']);
define('TOM_VINSTMATRIS', array_fill(10, 4, 0));
define('TOM_ODDSMATRIS', array_fill(0, MATCHANTAL, TOM_ODDSVEKTOR));
define('TECKENRYMDMATRIS', array_fill(0, MATCHANTAL, TECKENRYMD));
define('NOLLRAD', array_fill(0, MATCHANTAL, 0));
define('TOMRAD', array_fill(0, MATCHANTAL, ''));
define('PLATT_ODDSMATRIS', range(1, 3 * MATCHANTAL));
define('MATCHKOLUMNER', range(1, MATCHANTAL));

define('AF_MIN', 1);
define('AF_MAX', MATCHRYMD);
define('AF_STD', 5);

define('EXPONENT', mb_str_split('⁰¹²³⁴⁵⁶⁷⁸⁹'));
define('INDEX', mb_str_split('₀₁₂₃₄₅₆₇₈₉'));

define('UTFALL_PER_HALVGARDERINGAR', [
	[8192, 53248, 159744, 292864, 366080, 329472, 219648, 109824, 41184, 11440, 2288, 312, 26, 1],
	[4096, 32768, 116736, 247808, 352000, 354816, 261888, 143616, 58608, 17600, 3784, 552, 49, 2],
	[2048, 19456, 81408, 199936, 323840, 367488, 302016, 182688, 81576, 26620, 6182, 969, 92, 4],
	[1024, 11264, 54528, 154112, 284800, 365184, 335904, 225408, 110772, 39500, 9961, 1686, 172, 8],
	[512, 6400, 35328, 113920, 239552, 347616, 359328, 268944, 146322, 57361, 15800, 2904, 320, 16],
	[256, 3584, 22272, 81152, 193120, 316800, 368880, 309360, 187425, 81290, 24616, 4944, 592, 32],
	[128, 1984, 13728, 55984, 149720, 276660, 362910, 342105, 232020, 112060, 37568, 8304, 1088, 64],
	[64, 1088, 8304, 37568, 112060, 232020, 342105, 362910, 276660, 149720, 55984, 13728, 1984, 128],
	[32, 592, 4944, 24616, 81290, 187425, 309360, 368880, 316800, 193120, 81152, 22272, 3584, 256],
	[16, 320, 2904, 15800, 57361, 146322, 268944, 359328, 347616, 239552, 113920, 35328, 6400, 512],
	[8, 172, 1686, 9961, 39500, 110772, 225408, 335904, 365184, 284800, 154112, 54528, 11264, 1024],
	[4, 92, 969, 6182, 26620, 81576, 182688, 302016, 367488, 323840, 199936, 81408, 19456, 2048],
	[2, 49, 552, 3784, 17600, 58608, 143616, 261888, 354816, 352000, 247808, 116736, 32768, 4096],
	[1, 26, 312, 2288, 11440, 41184, 109824, 219648, 329472, 366080, 292864, 159744, 53248, 8192]
]);
