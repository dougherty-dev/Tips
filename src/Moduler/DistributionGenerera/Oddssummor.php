<?php

/**
 * Klass Oddssummor.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\DistributionGenerera;

/**
 * Klass Oddssummor.
 */
class Oddssummor extends Spara {
	/**
	 * Beräkna oddssummor för aktuella sannolikheter.
	 * @return float[]
	 */
	protected function oddssummor(): array {
		$oddssumma_utfall = 0.0;
		$oddssumma_min = 0.0;
		$oddssumma_max = 0.0;

		foreach ($this->tips->odds->sannolikheter as $k => $odds) {
			if ($this->tips->utdelning->tipsrad_012) {
				$oddssumma_utfall += $odds[$this->tips->utdelning->tipsrad_012[$k]];
			}

			$oddssumma_min += ne_min($odds);
			$oddssumma_max += ne_max($odds);
		}

		return [
			round($oddssumma_min, 2),
			round($oddssumma_max, 2),
			round($oddssumma_utfall, 2)
		];
	}
}
