<?php

/**
 * Klass Tipsresultat.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

use Tips\Egenskaper\Varden;

/**
 * Klass Tipsresultat.
 */
class Tipsresultat {
	use Varden;

	protected string $site;

	/**
	 * Sätt sajt.
	 */
	public function __construct(private Spel $spel) {
		$this->hämta_värden($this->spel->db);
		$this->site = SVENSKA_SPEL_API_URL . "{$this->spel->speltyp->produktnamn()}";
	}

	/**
	 * Hämta tipsresultat.
	 */
	public function hämta_tipsresultat(Tips &$tips): bool {
		$url = "{$this->site}/draws/{$tips->spel->omgång}/result?accesskey={$this->api}";
		$objekt = hämta_objekt($url);

		/**
		 * Säkerställ att data från Svenska spel finns, eller återvänd.
		 */
		if ($objekt === null || !isset($objekt->result)) { // Inga data.
			$tips->spel->db->logg->logga(self::class . ": ❌ Inget tipsresultat. ({$tips->spel->omgång})");
			return false;
		}

		/**
		 * Vi har tipsdata från Svenska spel. Extrahera och returnera.
		 */
		$tips->utdelning->utdelning = array_map(fn (int $index): int =>
			(int) flyttal($objekt->result->distribution[$index]->amount), array_keys($tips->utdelning->utdelning));

		$tips->utdelning->vinnare = array_map(fn (int $index): int =>
			$objekt->result->distribution[$index]->winners, array_keys($tips->utdelning->vinnare));

		$tips->utdelning->tipsrad = '';
		foreach ($objekt->result->events as $index => $event) {
			$tips->matcher->match[$index] = $event->description;
			$tips->matcher->resultat[$index] = $event->outcomeScore;
			$tips->matcher->matchstatus[$index] = ($event->cancelled) ? 0 : 1;
			$tips->utdelning->tipsrad .= $event->outcome;
		}

		$tips->matcher->komplett = !in_array(0, $tips->matcher->matchstatus, true);
		$tips->utdelning->tipsrad_012 = symboler_till_siffror($tips->utdelning->tipsrad);
		$tips->spel->db->logg->logga(self::class . ": ✅ Hämtade tipsresultat. ({$tips->spel->omgång})");
		return true;
	}
}
