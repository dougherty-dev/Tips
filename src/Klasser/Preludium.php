<?php

/**
 * Klass Preludium.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

/**
 * Autoload, konstanter och funktioner.
 * Fungerar som bootstrap för systemet.
 */
require_once dirname(__FILE__) . '/../../vendor/autoload.php';
require_once dirname(__FILE__) . '/../funktioner/konstanter.php';
require_once FUNKTIONER . '/funktioner.php';

/**
 * Klass Preludium.
 * Instantieras vid ajax-anrop.
 */
final class Preludium extends Felhanterare {
	/**
	 * Definiera miljö och session.
	 */
	public function __construct() {
		/**
		 * Lokalitet.
		 */
		setlocale(LC_TIME, 'sv_SE');

		/**
		 * Felhantering.
		 */
		error_reporting(0);
		ini_set('display_errors', '0');

		/**
		 * Slå på felrapportering vid enhetstestning.
		 */
		if (defined('UNITTEST')) {
			error_reporting(E_ALL);
			ini_set('display_errors', '1');
		}

		/**
		 * Även användardefinierad felhantering.
		 * Men ingen egen felhanterare vid enhetstest.
		 */
		if (defined('FELRAPPORTERING') && !defined('UNITTEST')) {
			error_reporting(E_ALL);
			ini_set('display_errors', '1');
			set_error_handler([$this, 'felhanterare']);
		}

		/**
		 * Ingen resursbegränsning för minne och tid.
		 */
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', '0');

		/**
		 * Teckenkodning. Inte alla servrar använder UTF-8 nativt.
		 */
		mb_internal_encoding('UTF-8');
		mb_http_output('UTF-8');

		/**
		 * Sessionshantering.
		 */
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
	}
}
