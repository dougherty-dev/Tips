<?php

/**
 * Egenskap Konstanter.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Kluster;

/**
 * Egenskap Konstanter.
 */
trait Konstanter {
	public const KLUSTER_MIN_ANTAL_MIN = 1;
	public const KLUSTER_MIN_ANTAL_MAX = 10000;
	public const KLUSTER_MIN_ANTAL_STD = 3;

	public const KLUSTER_MIN_RADIE_MIN = 1;
	public const KLUSTER_MIN_RADIE_MAX = 300;
	public const KLUSTER_MIN_RADIE_STD = 40;
}
