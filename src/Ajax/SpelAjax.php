<?php

/**
 * Klass SpelAjax.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Ajax;

use Tips\Klasser\Preludium;
use Tips\Klasser\Speltyp;
use Tips\Klasser\Spel;
use Tips\Egenskaper\Ajax;

/**
 * Ajaxanrop ligger utanför ordinarie ordning.
 */
require_once dirname(__FILE__) . '/../../vendor/autoload.php';
new Preludium();

/**
 * Klass SpelAjax.
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
 */
final class SpelAjax {
	use Ajax;

	private Spel $spel;

	/**
	 * Inititiera.
	 */
	public function __construct() {
		$this->spel = new Spel();
		$this->förgrena();
	}

	/**
	 * Ändra omgång.
	 * js/funktioner.js: ändra_omgång
	 */
	private function ändra_omgång(): void {
		$omgång = (int) filter_var($_REQUEST['ändra_omgång'], FILTER_VALIDATE_INT);
		if ($omgång <= 0) {
			return;
		}

		if ($this->spel->omgång_existerar($omgång)) {
			$this->spel->omgång = $omgång;
			$this->spel->sekvens = -1;
			$this->spel->hämta_sekvenser();
			$this->spel->spara_spel();
		}
	}

	/**
	 * Ändra speltyp.
	 * js/funktioner.js: ändra_speltyp
	 */
	private function ändra_speltyp(): void {
		$this->spel->speltyp = Speltyp::tryFrom((int) filter_var($_REQUEST['ändra_speltyp'], FILTER_VALIDATE_INT)) ?? Speltyp::Stryktipset;
		$this->spel->senaste_omgång();
	}

	/**
	 * Ändra sekvens.
	 * js/funktioner.js: ändra_sekvens
	 */
	private function ändra_sekvens(): void {
		$this->spel->sekvens = max(1, (int) filter_var($_REQUEST['ändra_sekvens'], FILTER_VALIDATE_INT));
		$this->spel->spara_spel();
	}

	/**
	 * Lägg till ny sekvens.
	 * js/funktioner.js: ny_sekvens
	 */
	private function ny_sekvens(): void {
		if (!filter_var($_REQUEST['ny_sekvens'], FILTER_VALIDATE_BOOLEAN)) {
			return;
		}

		$this->spel->sekvens = count($this->spel->sekvenser) > 0 ? (int) max($this->spel->sekvenser) + 1 : 1;
		$this->spel->spara_spel();
	}
}

new SpelAjax();
