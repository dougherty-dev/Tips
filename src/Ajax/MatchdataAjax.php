<?php

/**
 * Klass MatchdataAjax.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Ajax;

use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\Speltyp;
use Tips\Klasser\Tips;
use Tips\Klasser\Sekvenser;
use Tips\Egenskaper\Ajax;

/**
 * Ajaxanrop ligger utanför ordinarie ordning.
 */
require_once dirname(__FILE__) . '/../../vendor/autoload.php';
new Preludium();

/**
 * Klass MatchdataAjax.
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod", "PHPMD.UnusedPrivateField")
 */
final class MatchdataAjax extends Nedbrytning {
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
	 * Spara matchdata.
	 * js/funktioner.js: spara_matchdata
	 */
	private function spara_matchdata(): void {
		$_REQUEST['spara_matchdata'] = (string) filter_var($_REQUEST['spara_matchdata'], FILTER_SANITIZE_URL);
		// dumpa_text(dumpa_objekt($this->tips));
		parse_str($_REQUEST['spara_matchdata'], $matchdata);

		/**
		 * Omgångsdata.
		 */
		$this->tips->spel->omgång = (int) $matchdata['omgång'];
		$this->tips->utdelning->år = (int) $matchdata['år'];
		$this->tips->utdelning->vecka = (int) $matchdata['vecka'];

		$this->tips->utdelning->tipsrad = (string) filter_var($matchdata['tipsrad'], FILTER_SANITIZE_SPECIAL_CHARS);
		$this->tips->utdelning->tipsrad_012 = symboler_till_siffror($this->tips->utdelning->tipsrad);

		/**
		 * Utdelning och antal vinnare i respektive vinstkategori.
		 */
		foreach (range(0, 2) as $i) {
			$this->tips->utdelning->utdelning[$i] = (int) filter_var($matchdata['utdelning'][$i], FILTER_VALIDATE_INT);
			$this->tips->utdelning->vinnare[$i] = (int) filter_var($matchdata['vinnare'][$i], FILTER_VALIDATE_INT);
		}

		/**
		 * Matchstatus.
		 */
		$this->tips->matcher->matchstatus = array_map(fn ($i): int =>
			(int) filter_var($matchdata['matchstatus'][$i], FILTER_VALIDATE_INT), range(0, MATCHANTAL - 1));

		/**
		 * Odds och streck.
		 */
		$this->bryt_ned_prediktioner($matchdata['odds'], $this->tips->odds->prediktioner);
		$this->bryt_ned_prediktioner($matchdata['streck'], $this->tips->streck->prediktioner);

		$this->tips->matcher->spelstopp = (string) filter_var($matchdata['spelstopp'], FILTER_SANITIZE_SPECIAL_CHARS);

		/**
		 * Matcher.
		 */
		$this->bryt_ned_vektor($matchdata['lag'], $this->tips->matcher->match);
		$this->bryt_ned_vektor($matchdata['resultat'], $this->tips->matcher->resultat);

		$this->tips->spara_tips();
		(new Sekvenser($this->tips->spel))->traversera_sekvenser();

		$this->spel->db->spara_backup();
	}
}

new MatchdataAjax();
