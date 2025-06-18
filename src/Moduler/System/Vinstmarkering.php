<?php

/**
 * Klass Vinstmarkering.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\System;

/**
 * Klass Vinstmarkering.
 */
final class Vinstmarkering {
	/**
	 * Visa vinst eller förlust.
	 * @param string[] $matris
	 * @param string[] $arr
	 */
	public function vinstfärg(array &$matris, array $arr, string $tecken): void {
		foreach (array_keys($matris) as $index) {
			$förlust = $index === 1 ? ' förlust' : ' storförlust';

			$matris[$index] = match (empty($arr[$index])) {
				true => '',
				default => ($arr[$index] == $tecken) ? ' vinst' : $förlust
			};
		}
	}
}
