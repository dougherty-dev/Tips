<?php

/**
 * Klass ModulerAjax.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Ajax;

use PDO;
use Tips\Klasser\Preludium;
use Tips\Egenskaper\Ajax;

/**
 * Ajaxanrop ligger utanför ordinarie ordning.
 */
require_once dirname(__FILE__) . '/../../vendor/autoload.php';
new Preludium();

/**
 * Klass ModulerAjax.
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
 */
final class ModulerAjax {
	use Ajax;

	/**
	 * Inititiera.
	 */
	public function __construct() {
		$this->förgrena();
	}

	/**
	 * Uppdatera moduldata.
	 * js/funktioner.js: uppdatera_moduler
	 */
	private function uppdatera_moduler(): void {
		$moduler = [];
		if (!is_string($_REQUEST['uppdatera_moduler'])) {
			return;
		}
		parse_str($_REQUEST['uppdatera_moduler'], $moduler);

		if (is_array($moduler['moduler'])) {
			foreach ($moduler['moduler'] as $i => $modul) {
				$sats = $this->db->instans->prepare("UPDATE `moduler` SET `prioritet`=:prioritet WHERE `namn`=:namn");
				$sats->bindValue(':prioritet', $i + 1, PDO::PARAM_INT);
				$sats->bindValue(':namn', $modul, PDO::PARAM_STR);
				$sats->execute();
			}
		}
	}
}

new ModulerAjax();
