<?php

/**
 * Klass Generera.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

use Tips\Egenskaper\Varden;
use Tips\Klasser\Generera\Visa;

/**
 * Klass Generera.
 * Generera tipsrader via parallella processer.
 */
final class Generera extends Visa {
	use Varden;

	/**
	 * Initiera.
	 * Uppdatera konstruktor.
	 * Generera rader, spara temporärt samt visa resultatet.
	 */
	public function __construct(protected Tips $tips) {
		parent::__construct($tips);
		$this->hämta_värden($this->tips->spel->db);
		$this->generera_tipsrader();
		$this->plotta_genererad_tipsgraf();
		$this->spara_genererade_tipsrader();
		$this->visa_genererat_resultat();
		$this->tips->spel->db->logg->logga(self::class . ": ✅ Genererade rader. ({$this->tips->spel->omgång})");
	}

	/**
	 * Visa genererade rader samt graf.
	 */
	private function visa_genererat_resultat(): void {
		/**
		 * Begränsa till 50 visade rader under genereringen.
		 */
		$spelade_rader = '';
		$min = min(50, $this->antal_utvalda_rader);

		/**
		 * Bilda radtext.
		 */
		for ($index = 0; $index < $min; $index++) {
			$tipsrad = siffror_till_symboler($this->tips->spelade->tipsvektor[$index]);
			$spelade_rader .= t(7, "<code>$tipsrad</code><br>");
		}

		/**
		 * Bilda tipsgraf.
		 * Vinstkors sätts för att man kan generera en redan avgjord avgång i testsyfte.
		 * Kombinationsgraf renderas i förekommande fall.
		 */
		$this->graf->vinstkors($this->tips->utdelning->tipsrad_012);
		$this->graf->spara_tipsgraf($this->bildfil);

		$this->visa($spelade_rader);
	}
}
