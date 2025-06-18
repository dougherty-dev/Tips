<?php

/**
 * Klass MonteCarlo.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\FANNGenerera;

/**
 * Klass MonteCarlo.
 */
class MonteCarlo extends Traningsdata {
	protected int $mc_antal_punkter = 3 * 729; // = 3⁶, delar partitioner av 3 för parallell behandling

	/**
	 * Find limits using Monte Carlo randomization.
	 * @return array<int, float[]>
	 */
	protected function monte_carlo(): array {
		$mängder = [];
		$index = 0;
		do {
			$limit_1 = -random_int(30, 100) / 100.0;
			$limit_1X = $limit_1 + random_int(25, 50) / 100.0;
			$limit_X2 = $limit_1X + random_int(25, 50) / 100.0;
			if ($limit_X2 > 0.95) {
				$limit_X2 = 0.95;
			}

			$limiter = [$limit_1, $limit_1X, $limit_X2];
			if (!in_array($limiter, $mängder, true)) {
				$mängder[] = $limiter;
				$index++;
			}
		} while ($index < $this->mc_antal_punkter);
		return $mängder;
	}
}
