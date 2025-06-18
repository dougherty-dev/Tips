<?php

/**
 * Egenskap Konstanter.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Vinstgraf;

/**
 * Egenskap Konstanter.
 */
trait Konstanter {
	public const string FIL = '/vinstgraf.png';

	public const int UTDELNING_13_MIN_MIN = 100;
	public const int UTDELNING_13_MIN_MAX = 1_000_000;
	public const int UTDELNING_13_MIN_STD = 10_000;

	public const int UTDELNING_13_MAX_MIN = 1_000;
	public const int UTDELNING_13_MAX_MAX = MAXVINST;
	public const int UTDELNING_13_MAX_STD = 1_000_000;
}
