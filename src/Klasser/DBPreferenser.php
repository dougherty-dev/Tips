<?php

/**
 * Klass DBPreferenser.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

use Tips\Klasser\DBPreferenser\Validering;

/**
 * Klass DBPreferenser.
 */
final class DBPreferenser extends Validering {
	/**
	 * Jämför värden i preferenser.
	 */
	public function komparera_preferenser(float &$tal1, float &$tal2, float $st1, float $st2, string $pref1, string $pref2): void {
		if ($tal2 <= $tal1) {
			$this->spara_preferens($pref1, strval($tal1 = $st1));
			$this->spara_preferens($pref2, strval($tal2 = $st2));
		}
	}

	/**
	 * Jämför heltalsvärden i preferenser.
	 */
	public function int_komparera_preferenser(int &$tal1, int &$tal2, int $st1, int $st2, string $pref1, string $pref2): void {
		if ($tal2 <= $tal1) {
			$this->spara_preferens($pref1, strval($tal1 = $st1));
			$this->spara_preferens($pref2, strval($tal2 = $st2));
		}
	}
}
