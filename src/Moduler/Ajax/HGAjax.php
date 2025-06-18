<?php

/**
 * Klass HGAjax.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Ajax;

use Tips\Klasser\Preludium;
use Tips\Egenskaper\Ajax;
use Tips\Moduler\HG\Konstanter;

/**
 * Ajaxanrop ligger utanför ordinarie ordning.
 */
require_once dirname(__FILE__) . '/../../../vendor/autoload.php';
new Preludium();

/**
 * Klass HGAjax.
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
 */
final class HGAjax {
	use Ajax;
	use Konstanter;

	public function __construct() {
		$this->förgrena();
	}

	/**
	 * Håll HG inom godtagbara intervall.
	 */
	private function hg_min(): void {
		$this->db_preferenser->validera_indata('hg_min', self::HG_MIN, self::HG_MAX, self::HG_STD, 'hg.hg_min');
	}

	/**
	 * Spara attraktionsfaktor.
	 */
	private function attraktionsfaktor(): void {
		$this->ändra_attraktionsfaktor('hg');
	}
}

new HGAjax();
