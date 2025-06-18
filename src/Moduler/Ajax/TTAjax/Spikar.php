<?php

/**
 * Klass Spikar.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Ajax\TTAjax;

use Tips\Moduler\TT\Konstanter;
use Tips\Klasser\Databas;
use Tips\Klasser\DBPreferenser;

/**
 * Klass Spikar.
 */
class Spikar {
	use Konstanter;

	protected Databas $db;
	protected DBPreferenser $db_preferenser;

	/**
	 * Initiera.
	 */
	public function __construct() {
		$this->db = new Databas();
		$this->db_preferenser = new DBPreferenser($this->db);
	}

	/**
	 * Spara spikar för Topptipset.
	 */
	protected function tt_spikar(): void {
		$antal_spikar = array_fill(0, self::TT_MAX_SPIKFÄLT, 0);

		$_REQUEST['tt_spikar'] = is_string($_REQUEST['tt_spikar']) ?
			$_REQUEST['tt_spikar'] : '';
		parse_str($_REQUEST['tt_spikar'], $spikar);
		$spikar = (array) array_values($spikar)[0];

		$_REQUEST['tt_andel_spikar'] = is_string($_REQUEST['tt_andel_spikar']) ?
			$_REQUEST['tt_andel_spikar'] : '';
		parse_str($_REQUEST['tt_andel_spikar'], $andel_spikar);
		$andel_spikar = (array) array_values($andel_spikar)[0];

		/**
		 * Kontrollera format.
		 */
		foreach ($spikar as $index => $spik) {
			$spik = (array) filter_var_array((array) $spik, FILTER_SANITIZE_SPECIAL_CHARS);
			$spikar[$index] = $spik;

			/**
			 * Räkna upp antal spikar.
			 */
			foreach ($spik as $vektor) {
				if ($vektor != TOM_STRÄNGVEKTOR) {
					$antal_spikar[$index]++;
				}
			}

			$andel_spikar[$index] = min($andel_spikar[$index], $antal_spikar[$index]);
		}

		$this->db_preferenser->spara_preferens(
			"topptips.spikar",
			implode(',', array_merge([], ...array_merge([], ...$spikar)))
		);

		$this->db_preferenser->spara_preferens(
			"topptips.andel_spikar",
			implode(',', $andel_spikar)
		);

		echo true;
	}
}
