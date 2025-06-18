<?php

/**
 * Klass Vinstmarkering.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

/**
 * Klass Vinstmarkering.
 */
final class Vinstmarkering {
	/**
	 * Visa vinst eller förlust.
	 * @param string[] $k
	 * @param string[] $arr
	 */
	public function vinstfärg(array &$k, array $arr, string $tecken): void {
		foreach (array_keys($k) as $i) {
			$förlust = $i === 1 ? ' förlust' : ' storförlust';

			$k[$i] = match (empty($arr[$i])) {
				true => '',
				default => ($arr[$i] == $tecken) ? ' vinst' : $förlust
			};
		}
	}
}
