<?php

/**
 * Klass AutospikAjax.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Ajax;

use Tips\Klasser\Preludium;
use Tips\Egenskaper\Ajax;
use Tips\Moduler\Autospik\Konstanter;

/**
 * Ajaxanrop ligger utanför ordinarie ordning.
 */
require_once dirname(__FILE__) . '/../../../vendor/autoload.php';
new Preludium();

/**
 * Klass AutospikAjax.
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
 */
final class AutospikAjax {
	use Ajax;
	use Konstanter;

	public function __construct() {
		$this->förgrena();
	}

	/**
	 * Spara spikar i preferenser.
	 */
	private function valda_spikar(): void {
		$this->db_preferenser->validera_indata('valda_spikar', self::AS_MIN, self::AS_MAX, self::AS_STD, 'autospik.valda_spikar');
	}

	/**
	 * Spara attraktionsfaktor.
	 */
	private function attraktionsfaktor(): void {
		$this->ändra_attraktionsfaktor('autospik');
	}
}

new AutospikAjax();
