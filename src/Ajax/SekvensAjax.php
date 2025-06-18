<?php

/**
 * Klass SekvensAjax.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Ajax;

use Tips\Klasser\Preludium;
use Tips\Klasser\Speltyp;
use Tips\Klasser\Spel;
use Tips\Klasser\Tips;
use Tips\Egenskaper\Ajax;

/**
 * Ajaxanrop ligger utanför ordinarie ordning.
 */
require_once dirname(__FILE__) . '/../../vendor/autoload.php';
new Preludium();

/**
 * Klass SekvensAjax.
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
 */
final class SekvensAjax {
	use Ajax;

	private Spel $spel;
	private Tips $tips;

	/**
	 * Inititiera.
	 */
	public function __construct() {
		$this->spel = new Spel();
		$this->tips = new Tips($this->spel);
		$this->förgrena();
	}

	/**
	 * Radera sekvens.
	 * js/funktioner.js: radera_sekvens, omgång, speltyp
	 */
	private function radera_sekvens(): void {
		$sekvens = (int) filter_var($_REQUEST['radera_sekvens'], FILTER_VALIDATE_INT);
		$omgång = (int) filter_var($_REQUEST['omgång'], FILTER_VALIDATE_INT);
		$speltyp = Speltyp::From((int) filter_var($_REQUEST['speltyp'], FILTER_VALIDATE_INT));

		foreach ($this->tips->moduler->m_moduler as $m) {
			if (method_exists($m, 'radera_sekvens')) {
				$m->radera_sekvens($omgång, $speltyp, $sekvens);
			}
		}

		$this->tips->spelade->radera_sekvens($omgång, $speltyp, $sekvens);
		$this->spel->db->radera_sekvens('spel', $omgång, $speltyp, $sekvens);
		$this->spel->hämta_sekvenser();
		$this->spel->spara_spel();
	}
}

new SekvensAjax();
