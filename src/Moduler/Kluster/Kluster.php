<?php

/**
 * Klass Kluster.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Kluster;

class Kluster extends HamtaKoordinater {
	/**
	 * Kluster.
	 * @param int[] $punkt
	 * @param array<int, int[]> $koordinater
	 * @return mixed[]
	 */
	protected function kluster(array $punkt, array &$koordinater): array {
		[$ukoord, $vkoord] = $punkt;
		$kluster = [$punkt];

		foreach ($koordinater as $index => [$x, $y]) {
			$radie = sqrt(pow($x - $ukoord, 2) + pow($y - $vkoord, 2));
			if ($radie > 0 && $radie < $this->min_radie) {
				$ny_punkt = [$x, $y];
				unset($koordinater[$index]);
				return [...$kluster, ...$this->kluster($ny_punkt, $koordinater)];
			}
		}
		return $kluster;
	}
}
