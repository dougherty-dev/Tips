<?php

/**
 * Klass AndelAjax.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Ajax;

use Tips\Klasser\Preludium;
use Tips\Egenskaper\Ajax;

/**
 * Ajaxanrop ligger utanför ordinarie ordning.
 */
require_once dirname(__FILE__) . '/../../../vendor/autoload.php';
new Preludium();

/**
 * Klass AndelAjax.
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
 */
final class AndelAjax {
	use Ajax;

	public function __construct() {
		$this->förgrena();
	}

	/**
	 * Håll andel ettor inom godtagbara intervall.
	 */
	private function andel_1_min(): void {
		$andel_1_min = $this->db_preferenser->validera_indata('andel_1_min', 0, 13, 3, 'andel.andel_1_min');
		$andel_1_max = $this->db_preferenser->validera_indata('andel_1_max', 0, 13, 8, 'andel.andel_1_max');
		$this->db_preferenser->komparera_preferenser($andel_1_min, $andel_1_max, 3, 8, 'andel.andel_1_min', 'andel.andel_1_max');
	}

	/**
	 * Håll andel kryss inom godtagbara intervall.
	 */
	private function andel_x_min(): void {
		$andel_x_min = $this->db_preferenser->validera_indata('andel_x_min', 0, 13, 1, 'andel.andel_x_min');
		$andel_x_max = $this->db_preferenser->validera_indata('andel_x_max', 0, 13, 6, 'andel.andel_x_max');
		$this->db_preferenser->komparera_preferenser($andel_x_min, $andel_x_max, 1, 6, 'andel.andel_x_min', 'andel.andel_x_max');
	}

	/**
	 * Håll andel tvåor inom godtagbara intervall.
	 */
	private function andel_2_min(): void {
		$andel_2_min = $this->db_preferenser->validera_indata('andel_2_min', 0, 13, 2, 'andel.andel_2_min');
		$andel_2_max = $this->db_preferenser->validera_indata('andel_2_max', 0, 13, 7, 'andel.andel_2_max');
		$this->db_preferenser->komparera_preferenser($andel_2_min, $andel_2_max, 2, 7, 'andel.andel_2_min', 'andel.andel_2_max');
	}

	/**
	 * Spara attraktionsfaktor.
	 */
	private function attraktionsfaktor(): void {
		$this->ändra_attraktionsfaktor('andel');
	}
}

new AndelAjax();
