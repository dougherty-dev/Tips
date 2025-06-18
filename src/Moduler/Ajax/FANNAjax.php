<?php

/**
 * Klass FANNAjax.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Ajax;

use Tips\Klasser\Preludium;
use Tips\Egenskaper\Ajax;
use Tips\Moduler\FANN\Konstanter;

/**
 * Ajaxanrop ligger utanför ordinarie ordning.
 */
require_once dirname(__FILE__) . '/../../../vendor/autoload.php';
new Preludium();

/**
 * Klass FANNAjax.
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
 */
final class FANNAjax {
	use Ajax;
	use Konstanter;

	public function __construct() {
		$this->förgrena();
	}

	/**
	 * Spara minvärde.
	 */
	private function fann_min(): void {
		$this->db_preferenser->validera_indata('fann_min', self::FANN_MIN, self::FANN_MAX, self::FANN_STD, 'fann.fann_min');
	}

	/**
	 * Spara feltolerans.
	 */
	private function fann_feltolerans(): void {
		$this->db_preferenser->validera_indata('fann_feltolerans', self::FANN_FELTOLERANS_MIN, self::FANN_FELTOLERANS_MAX, self::FANN_FELTOLERANS_STD, 'fann.fann_feltolerans');
	}

	/**
	 * Spara attraktionsfaktor.
	 */
	private function attraktionsfaktor(): void {
		$this->ändra_attraktionsfaktor('fann');
	}
}

new FANNAjax();
