<?php

/**
 * Egenskap Tick.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Egenskaper;

/**
 * Egenskap Tick.
 */
trait Tick {
	protected int $tick = 0;
	protected int $attraktionsfaktor = AF_STD;

	/**
	 * Statisk räknare.
	 */
	protected function tick(): bool {
		$this->tick = ($this->tick + 1) % $this->attraktionsfaktor;
		return $this->tick === 0;
	}

	/**
	 * Visa faktor för nyttjandefrekvens av modul.
	 */
	protected function attraktionsfaktor(int $attraktionsfaktor, string $id): string {
		return '| <span class="pekare" id="' . $id . '_min">🔻</span> <input class="nummer" type="number" min="1" max="1594323" autocomplete="off" id="' .
			$id . '" value="' . $attraktionsfaktor . '"> <span class="pekare" id="' . $id . '_max">🔺</span> ';
	}
}
