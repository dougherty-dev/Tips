<?php

/**
 * Enum Speltyp.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

/**
 * Enum Speltyp.
 */
enum Speltyp: int {
	case Stryktipset = 1;
	case Europatipset = 2;

	/**
	 * Hämta produktnamn.
	 */
	public function produktnamn(): string {
		return match ($this) {
			static::Stryktipset => 'stryktipset',
			static::Europatipset => 'europatipset'
		};
	}
}
