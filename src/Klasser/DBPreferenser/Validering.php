<?php

/**
 * Klass Validering.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\DBPreferenser;

/**
 * Klass Validering.
 */
class Validering extends Intervall {
	/**
	 * Validera indata.
	 */
	public function validera_indata(
		string $rad,
		int|float $min,
		int|float $max,
		int|float $standard,
		string $pref
	): int|float {
		$värde = is_int($min) ? (int) filter_var($_REQUEST[$rad], FILTER_VALIDATE_INT) : (float) filter_var($_REQUEST[$rad], FILTER_VALIDATE_FLOAT);
		∈($standard, $min, $max) or $standard = $min;
		∈($värde, $min, $max) or $värde = $standard;
		$this->spara_preferens($pref, (string) $värde);
		return $värde;
	}
}
