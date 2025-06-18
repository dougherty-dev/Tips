<?php

/**
 * Intervall class.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\DBPreferenser;

/**
 * Intervall class.
 */
class Intervall extends InOut {
	/**
	 * Is preference in a given interval?
	 */
	public function preferens_i_intervall(float &$tal, float $min, float $max, float $grund, string $pref): void {
		$oreferens = $this->hämta_preferens($pref);
		$tal = (float) $oreferens;
		∈($tal, $min, $max) or $this->spara_preferens($pref, strval($grund));
	}

	/**
	 * Is preference in a given integer interval?
	 */
	public function int_preferens_i_intervall(int &$tal, int $min, int $max, int $grund, string $pref): void {
		$oreferens = $this->hämta_preferens($pref);
		$tal = (int) $oreferens;
		∈($tal, $min, $max) or $this->spara_preferens($pref, strval($grund));
	}
}
