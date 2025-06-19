<?php

/**
 * Klass TTHamtaTopptips.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

use Tips\Egenskaper\Varden;
use Tips\Egenskaper\Ajax;
use Tips\Moduler\TT;

/**
 * Klass TTHamtaTopptips.
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
 */
final class TTHamtaTopptips extends Spara {
	use Varden;
	use Ajax;

	/**
	 * Initiera. Uppdatera konstruktor.
	 */
	public function __construct(private TT $tt) {
		parent::__construct($this->tt);
		$this->hämta_värden($this->tt->utdelning->spel->db);
		$this->förgrena();
	}

	/**
	 * Hämta topptips vid Svenska spel.
	 * $_REQUEST['hämta_topptips']
	 */
	private function hämta_topptips(): bool {
		$url = SVENSKA_SPEL_API_URL . "{$this->tt->typer['produkt']}/draws?accesskey={$this->api}";

		// $objekt = (object) json_decode((string) file_get_contents(JSONFIL));
		$objekt = hämta_objekt($url);

		/**
		 * Säkerställ att data från Svenska spel finns.
		 */
		$draws = match (true) {
			$objekt === null => false,
			isset($objekt->draws) && $objekt->draws => $objekt->draws[0],
			default => false
		};

		/**
		 * Återvänd i annat fall.
		 */
		if (!$draws || !$draws->drawNumber) {
			$this->tt->utdelning->spel->db->logg->logga(self::class . ": ❌ Inga TT-data.");
			return false;
		}

		/**
		 * Vi har tipsdata från Svenska spel.
		 * Extrahera och returnera.
		 */
		$this->tt->omgång = $draws->drawNumber;
		$this->tt->omsättning = intval($draws->turnover);
		$this->tt->spelstopp = strval($draws->closeTime);
		$this->tt->överskjutande = intval($draws->fund->rolloverIn);
		$this->tt->extrapengar = intval($draws->fund->extraMoney);

		/**
		 * Extrahera omgångsdata.
		 */
		$this->bearbeta_tipsdata($draws->events);

		$this->tt->utdelning->spel->db->logg->logga(self::class . ": ✅ Hämtade TT-data. ({$this->tt->omgång})");
		$this->spara_data();

		return true;
	}

	/**
	 * Plocka ut data från JSON-objekt.
	 * @param object[] $events
	 */
	private function bearbeta_tipsdata(array $events): void {
		foreach ($events as $k => $event) {
			$index = intval($k);

			/**
			 * Plocka ut matcher.
			 */
			if (isset($event->participants)) {
				$this->tt->hemmalag[$index] = $event->participants[0]->name;
				$this->tt->bortalag[$index] = $event->participants[1]->name;
			}

			/**
			 * Plocka ut streck.
			 */
			if (isset($event->distribution)) {
				$this->tt->tt_streck[$index] = array_map(
					'floatval',
					[$event->distribution->home, $event->distribution->draw, $event->distribution->away]
				);

				/**
				 * Plocka ut odds.
				 */
				if (isset($event->odds->home, $event->odds->draw, $event->odds->away)) {
					$this->tt->tt_odds[$index] = $event->odds->home ? array_map(
						'flyttal',
						[$event->odds->home, $event->odds->draw, $event->odds->away]
					) : TOM_ODDSVEKTOR;
				}
			}
		}
	}
}
