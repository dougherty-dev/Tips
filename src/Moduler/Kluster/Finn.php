<?php

/**
 * Klass Finn.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Kluster;

/**
 * Klass Finn.
 */
class Finn extends Generera {
	/**
	 * Finn kluster.
	 */
	public function finn_kluster(): void {
		/**
		 * Måste nollställas.
		 */
		$this->rektanglar = [];
		$this->klustrade_rader = 0;
		$this->area = 0;

		$koordinater = $this->hämta_koordinater();
		$this->antal_rader = count($koordinater);

		$kluster = $this->generera_kluster($koordinater);

		foreach ($kluster as $grupp) {
			[$x_min, $y_min, $x_max, $y_max] = $this->minmaxkoordinater($grupp);

			if (count($grupp) >= $this->min_antal) {
				$this->area += ($x_max - $x_min) * ($y_max - $y_min);
				$this->rektanglar[] = [$x_min, $y_min, $x_max, $y_max];
				$this->graf->sätt_rektangel($x_min, $y_min, $x_max, $y_max, $this->graf->blå);
			}
		}

		$this->rektanglar($koordinater);
	}

	/**
	 * Hitta min och max för koordinater.
	 * @param mixed[] $grupp
	 * @return int[]
	 */
	private function minmaxkoordinater(array $grupp): array {
		[$x_min, $y_min, $x_max, $y_max] = [PHP_INT_MAX, PHP_INT_MAX, -1, -1];

		foreach ($grupp as [$ukoord, $vkoord]) {
			if (is_int($ukoord) && is_int($vkoord)) {
				[$x_min, $y_min, $x_max, $y_max] =
				[min($ukoord, $x_min), min($vkoord, $y_min), max($ukoord, $x_max), max($vkoord, $y_max)];
			}
		}

		return [
			max(0, $x_min - 10),
			max(0, $y_min - 10),
			min($this->graf->bredd, $x_max + 10),
			min($this->graf->höjd, $y_max + 10)
		];
	}
}
