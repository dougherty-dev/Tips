<?php

/**
 * Klass Tipsdata.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

/**
 * Klass Tipsdata.
 * @SuppressWarnings("PHPMD.BooleanArgumentFlag")
 */
final class Tipsdata extends Tipsresultat {
	/**
	 * Hämta tipsdata.
	 * Rest med boolesk flagga för senaste spel (= okänd omgång).
	 * Tanken var att hämta data fär äldre spel, men dessa är ofta ofullständiga.
	 */
	public function hämta_tipsdata(Tips &$tips, bool $senaste = true): bool {
		$url = ($senaste) ? "{$this->site}/draws?accesskey={$this->api}" :
			"{$this->site}/draws/{$tips->spel->omgång}?accesskey={$this->api}";
		$objekt = hämta_objekt($url);

		/**
		 * Säkerställ att data från Svenska spel finns.
		 */
		$draws = match (true) {
			$objekt === null => false,
			$senaste && isset($objekt->draws) && $objekt->draws => $objekt->draws[0],
			isset($objekt->draw) && $objekt->draw => $objekt->draw,
			default => false
		};

		/**
		 * Återvänd i annat fall.
		 */
		if (!$draws || !$draws->drawNumber) {
			$tips->spel->db->logg->logga(self::class . ": ❌ Inga tipsdata. ({$tips->spel->omgång})");
			return false;
		}

		/**
		 * Vi har tipsdata från Svenska spel. Ny omgång.
		 */
		$spel = new Spel();
		$spel->omgång = $draws->drawNumber;
		$spel->hämta_sekvenser();
		$spel->spara_spel();
		$tips = new Tips($spel);

		/**
		 * Extrahera JSON-data för omgång.
		 */
		$tips->utdelning->år = intval(substr($draws->closeTime, 0, 4));
		$tips->utdelning->vecka = intval(explode("-", $draws->drawComment)[1]);
		$tips->matcher->spelstopp = strval($draws->closeTime);

		/**
		 * Plocka ut matcher, odds och streck.
		 */
		foreach ($draws->events as $index => $event) {
			$tips->matcher->match[$index] = $event->description;
			$tips->streck->prediktioner[$index] = array_map(
				'floatval',
				[$event->distribution->home, $event->distribution->draw, $event->distribution->away]
			);

			/**
			 * Ge nollvektor om oddsdata saknas för match.
			 */
			if (isset($event->odds->home, $event->odds->draw, $event->odds->away)) {
				$tips->odds->prediktioner[$index] = $event->odds->home ? array_map(
					'flyttal',
					[$event->odds->home, $event->odds->draw, $event->odds->away]
				) : TOM_ODDSVEKTOR;
			}
		}

		/**
		 * Logga och återvänd.
		 */
		$tips->spel->db->logg->logga(self::class . ": ✅ Hämtade tipsdata. ({$tips->spel->omgång})");
		return true;
	}
}
