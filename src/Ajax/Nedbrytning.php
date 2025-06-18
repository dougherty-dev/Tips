<?php

/**
 * Klass Nedbrytning.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Ajax;

/**
 * Klass Nedbrytning.
 * Hjälpfunktioner till MatcherdataAjax.
 */
class Nedbrytning {
	/**
	 * Bryt ned indata för kontroll av element.
	 * @param mixed[]|string $arr1
	 * @param string[] $arr2
	 */
	protected function bryt_ned_vektor(mixed $arr1, array &$arr2): void {
		if (is_array($arr1)) {
			foreach ($arr1 as $i => $resultat) {
				$arr2[$i] = (string) filter_var($resultat, FILTER_SANITIZE_SPECIAL_CHARS);
			}
		}
	}

	/**
	 * Bryt ned indata för kontroll av element.
	 * @param mixed[]|string $arr1
	 * @param array<float[]> $arr2
	 */
	protected function bryt_ned_prediktioner(mixed $arr1, array &$arr2): void {
		if (is_array($arr1)) {
			foreach ($arr1 as $i => $odds) {
				if (is_array($odds)) {
					foreach ($odds as $j => $single_odds) {
						$arr2[$i][$j] = (float) filter_var($single_odds, FILTER_VALIDATE_FLOAT);
					}
				}
			}
		}
	}
}
