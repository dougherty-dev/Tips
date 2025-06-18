<?php

/**
 * Klass TTAjax.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Ajax;

use Tips\Klasser\Preludium;
use Tips\Egenskaper\Ajax;
use Tips\Moduler\Ajax\TTAjax\Bokforing;

/**
 * Ajaxanrop ligger utanför ordinarie ordning.
 */
require_once dirname(__FILE__) . '/../../../vendor/autoload.php';
new Preludium();

/**
 * Klass TTAjax.
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
 */
final class TTAjax extends Bokforing {
	use Ajax;

	/**
	 * Inititiera.
	 * Uppdatera konstruktor.
	 */
	public function __construct() {
		parent::__construct();
		$this->förgrena();
	}

	/**
	 * Spara strategi för Topptipset.
	 * Strategi = anteckningsruta på sidan.
	 */
	private function tt_strategi(): void {
		$tt_strategi = filter_var($_REQUEST['tt_strategi'], FILTER_SANITIZE_SPECIAL_CHARS);
		$this->db_preferenser->spara_preferens("topptips.strategi", strval($tt_strategi));
	}

	/**
	 * Spara reduktionsdata för Topptipset.
	 */
	private function tt_reduktion(): void {
		$_REQUEST['tt_reduktion'] = is_string($_REQUEST['tt_reduktion']) ? $_REQUEST['tt_reduktion'] : '';
		parse_str($_REQUEST['tt_reduktion'], $reduktion);
		$reduktion = (array) array_values($reduktion)[0];
		$reduktion_platt = [];

		/**
		 * Platta ut struktur.
		 */
		foreach ($reduktion as $redux1) {
			if (is_array($redux1)) {
				foreach ($redux1 as $redux2) {
					if (is_string($redux2)) {
						$reduktion_platt[] = $redux2;
					}
				}
			}
		}
		$this->db_preferenser->spara_preferens("topptips.reduktion", implode(',', $reduktion_platt));

		echo true;
	}
}

new TTAjax();
