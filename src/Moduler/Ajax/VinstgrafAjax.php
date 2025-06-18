<?php

/**
 * Klass VinstgrafAjax.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Ajax;

use Tips\Klasser\Preludium;
use Tips\Egenskaper\Ajax;
use Tips\Moduler\Vinstgraf\Konstanter;

/**
 * Ajaxanrop ligger utanför ordinarie ordning.
 */
require_once dirname(__FILE__) . '/../../../vendor/autoload.php';
new Preludium();

/**
 * Klass VinstgrafAjax.
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
 */
final class VinstgrafAjax {
	use Ajax;
	use Konstanter;

	public function __construct() {
		$this->förgrena();
	}

	/**
	 * Spara intervall för vinst vid 13 rätt.
	 */
	private function utdelning_13_min(): void {
		$utdelning_13_min = (int) $this->db_preferenser->validera_indata(
			'utdelning_13_min',
			self::UTDELNING_13_MIN_MIN,
			self::UTDELNING_13_MIN_MAX,
			self::UTDELNING_13_MIN_STD,
			'vinstgraf.utdelning_13_min'
		);

		$utdelning_13_max = (int) $this->db_preferenser->validera_indata(
			'utdelning_13_max',
			self::UTDELNING_13_MAX_MIN,
			self::UTDELNING_13_MAX_MAX,
			self::UTDELNING_13_MAX_STD,
			'vinstgraf.utdelning_13_max'
		);

		$this->db_preferenser->komparera_preferenser(
			$utdelning_13_min,
			$utdelning_13_max,
			self::UTDELNING_13_MIN_STD,
			self::UTDELNING_13_MAX_STD,
			'vinstgraf.utdelning_13_min',
			'vinstgraf.utdelning_13_max'
		);
	}
}

new VinstgrafAjax();
