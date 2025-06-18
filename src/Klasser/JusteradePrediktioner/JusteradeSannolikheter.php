<?php

/**
 * Klass SparaHistorik.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\JusteradePrediktioner;

/**
 * Klass SparaHistorik.
 */
class JusteradeSannolikheter extends SparaHistorik {
	/**
	 * @var array<int, float[]> $justerade_sann
	 */
	public array $justerade_sann;

	/**
	 * Beräkna justerade sannolikheter.
	 * @param array<int, float[]> $sannolikheter
	 * @param array<int, float[]> $justering
	 * @return array<int, float[]>
	 */
	protected function justerade_sann(array $sannolikheter, array $justering): array {
		$justerade_sann = $sannolikheter;
		foreach ($sannolikheter as $i => $match) {
			foreach ($match as $j => $sannolikhet) {
				$index = procenttal($sannolikhet);
				$justerade_sann[$i][$j] = $justering[$j][$index];
			}
		}

		return $justerade_sann;
	}
}
