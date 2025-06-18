<?php

/**
 * Klass TipsdataAjax.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Ajax;

use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\Tips;
use Tips\Klasser\Sekvenser;
use Tips\Klasser\Tipsdata;
use Tips\Egenskaper\Ajax;

/**
 * Ajaxanrop ligger utanför ordinarie ordning.
 */
require_once dirname(__FILE__) . '/../../vendor/autoload.php';
new Preludium();

/**
 * Klass TipsdataAjax.
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
 */
final class TipsdataAjax {
	use Ajax;

	private Spel $spel;
	private Tips $tips;
	private Tipsdata $tipsdata;

	/**
	 * Inititiera.
	 */
	public function __construct() {
		$this->spel = new Spel();
		$this->tips = new Tips($this->spel);
		$this->tipsdata = new Tipsdata($this->spel);
		$this->förgrena();
	}

	/**
	 * Hämta data från Svenska spel.
	 * js/funktioner.js: hämta_tips
	 */
	private function hämta_tips(): void {
		$resultat = match ($_REQUEST['hämta_tips']) {
			'hämta_tipsresultat' => $this->tipsdata->hämta_tipsresultat($this->tips),
			'hämta_tipsdata' => $this->tipsdata->hämta_tipsdata($this->tips),
			default => false
		};

		if ($resultat) {
			$this->tips->spara_tips();
			(new Sekvenser($this->tips->spel))->traversera_sekvenser();
			$this->db->spara_backup();
		}
	}
}

new TipsdataAjax();
