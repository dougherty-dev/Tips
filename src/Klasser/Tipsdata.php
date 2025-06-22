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
	 * Tanken var att hämta data för äldre spel, men dessa är ofta ofullständiga.
	 */
	public function hämta_tipsdata(Tips &$tips, bool $senaste = true): bool {
		$objekt = hämta_objekt($this->url($tips, $senaste));

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

		$this->bearbeta_tipsdata($tips, $draws->events, $draws->closeTime, $draws->drawComment);

		/**
		 * Logga och återvänd.
		 */
		$tips->spel->db->logg->logga(self::class . ": ✅ Hämtade tipsdata. ({$tips->spel->omgång})");
		return true;
	}

	/**
	 * Bestäm URL.
	 */
	private function url(Tips $tips, bool $senaste): string {
		return ($senaste) ? "{$this->site}/draws?accesskey={$this->api}" :
			"{$this->site}/draws/{$tips->spel->omgång}?accesskey={$this->api}";
	}

	/**
	 * Plocka ut data från JSON-objekt.
	 * @param object[] $events
	 */
	private function bearbeta_tipsdata(Tips &$tips, array $events, string $close_time, string $comment): void {
		/**
		 * Extrahera JSON-data för omgång.
		 */
		$tips->utdelning->år = intval(substr($close_time, 0, 4));
		$tips->utdelning->vecka = intval(explode("-", $comment)[1]);
		$tips->matcher->spelstopp = strval($close_time);

		/**
		 * Plocka ut matcher, odds och streck.
		 */
		foreach ($events as $index => $event) {
			if (isset($event->description)) {
				$tips->matcher->match[$index] = $event->description;
			}

			if (isset($event->distribution)) {
				$tips->streck->prediktioner[$index] = array_map(
					'floatval',
					[$event->distribution->home, $event->distribution->draw, $event->distribution->away]
				);
			}

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
	}
}
