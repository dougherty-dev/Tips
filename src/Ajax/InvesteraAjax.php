<?php

/**
 * Klass InvesteraAjax.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Ajax;

use Tips\Klasser\Preludium;
use Tips\Egenskaper\Ajax;
use Tips\Klasser\Investera\Konstanter;

/**
 * Ajaxanrop ligger utanför ordinarie ordning.
 */
require_once dirname(__FILE__) . '/../../vendor/autoload.php';
new Preludium();

/**
 * Klass InvesteraAjax.
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
 */
final class InvesteraAjax {
	use Ajax;
	use Konstanter;

	/**
	 * Inititiera.
	 */
	public function __construct() {
		$this->förgrena();
	}

	/**
	 * Visa antal rader med investeringar.
	 * js/funktioner.js: visa_antal
	 */
	private function visa_antal(): void {
		if (!is_string($_REQUEST['visa_antal'])) {
			return;
		}

		$this->db_preferenser->validera_indata(
			$_REQUEST['visa_antal'],
			self::INVESTERA_VISA_MIN,
			self::INVESTERA_VISA_MAX,
			self::INVESTERA_VISA_STD,
			'investera.visa_antal'
		);
	}
}

new InvesteraAjax();
