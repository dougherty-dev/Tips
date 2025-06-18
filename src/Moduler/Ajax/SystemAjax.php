<?php

/**
 * Klass SystemAjax.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Ajax;

use Tips\Klasser\Preludium;
use Tips\Moduler\System\RKod;
use Tips\Moduler\System\Konstanter;
use Tips\Egenskaper\Ajax;
use Tips\Moduler\Ajax\SystemAjax\Garderingar;

/**
 * Ajaxanrop ligger utanför ordinarie ordning.
 */
require_once dirname(__FILE__) . '/../../../vendor/autoload.php';
new Preludium();

/**
 * Klass SystemAjax.
 * Hantera indata från användare via javascript.
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
 */
final class SystemAjax extends Garderingar {
	use Ajax;
	use Konstanter;

	/**
	 * Inititiera.
	 * Uppdatera konstruktor.
	 */
	public function __construct() {
		parent::__construct();
		$this->förgrena();
	}

	/**
	 * Spara status.
	 */
	private function status(): void {
		$status = filter_var($_REQUEST['status'], FILTER_VALIDATE_BOOLEAN);
		$id = filter_var($_REQUEST['id'], FILTER_SANITIZE_SPECIAL_CHARS);
		$this->db_preferenser->spara_preferens("system." . $id, strval($status));
	}

	/**
	 * Spara kodnamn och visa koddata.
	 */
	private function kod(): void {
		$kod = RKod::tryFrom((string) filter_var(
			$_REQUEST['kod'],
			FILTER_SANITIZE_FULL_SPECIAL_CHARS
		)) ?? RKod::cases()[0]; // säkerställ kodtyp

		$this->db_preferenser->spara_preferens('system.kod', $kod->value);
		echo <<< EOT
{$kod->koddata()}
EOT;
	}

	/**
	 * Platta till och spara reduktion.
	 */
	private function reduktion(): void {
		$_REQUEST['reduktion'] = is_string($_REQUEST['reduktion']) ? $_REQUEST['reduktion'] : '';
		parse_str($_REQUEST['reduktion'], $reduktion);
		$reduktion = (array) array_values($reduktion)[0];
		$reduktion_platt = [];
		/**
		 * Platta till reduktionsmatris.
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
		$this->db_preferenser->spara_preferens("system.reduktion", implode(',', $reduktion_platt));
		echo true;
	}

	/**
	 * Spara attraktionsfaktor.
	 */
	private function attraktionsfaktor(): void {
		$this->ändra_attraktionsfaktor('system');
	}
}

new SystemAjax();
