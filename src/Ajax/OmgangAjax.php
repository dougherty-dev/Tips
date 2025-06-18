<?php

/**
 * Klass OmgangAjax.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Ajax;

use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\Speltyp;
use Tips\Klasser\Tips;
use Tips\Klasser\Sekvenser;
use Tips\Klasser\Investera;
use Tips\Egenskaper\Ajax;

/**
 * Ajaxanrop ligger utanför ordinarie ordning.
 */
require_once dirname(__FILE__) . '/../../vendor/autoload.php';
new Preludium();

/**
 * Klass OmgangAjax.
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
 */
final class OmgangAjax {
	use Ajax;

	private Spel $spel;
	private Tips $tips;

	/**
	 * Init. Förgrena baserat på request.
	 */
	public function __construct() {
		$this->spel = new Spel();
		$this->tips = new Tips($this->spel);
		$this->förgrena();
	}

	/**
	 * Radera omgång.
	 * js/funktioner.js: radera_omgång, speltyp
	 */
	private function radera_omgång(): void {
		$omgång = (int) filter_var($_REQUEST['radera_omgång'], FILTER_VALIDATE_INT);
		$speltyp = Speltyp::From((int) filter_var($_REQUEST['speltyp'], FILTER_VALIDATE_INT));

		foreach ($this->tips->moduler->m_moduler as $m) {
			if (method_exists($m, 'radera_omgång')) {
				$m->radera_omgång($omgång, $speltyp);
			}
		}

		$this->tips->spelade->radera_omgång($omgång, $speltyp);
		$this->spel->radera_omgång($omgång, $speltyp);
	}

	/**
	 * Investera i sparat spel.
	 * js/funktioner.js: investera_sparad
	 */
	private function investera_sparad(): void {
		$investera_sparad = (int) filter_var($_REQUEST['investera_sparad'], FILTER_VALIDATE_INT);

		$this->tips->spelade->spara_genererade_tipsrader_db();
		$this->tips->spelade->spara_genererade_tipsrader_fil();
		$this->tips->spelade->generera_tipsgraf();

		if ($investera_sparad === 1) {
			(new Investera($this->tips))->investera();
		}
	}

	/**
	 * Spara preferens för strategi.
	 */
	private function strategi(): void {
		$strategi = filter_var($_REQUEST['strategi'], FILTER_SANITIZE_SPECIAL_CHARS);
		$this->db_preferenser->spara_preferens("strategi", strval($strategi));
	}
}

new OmgangAjax();
