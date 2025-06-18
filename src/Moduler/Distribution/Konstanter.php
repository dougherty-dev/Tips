<?php

/**
 * Egenskap Konstanter.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Distribution;

/**
 * Egenskap Konstanter.
 */
trait Konstanter {
	public const DISTRIBUTION_GRUND_MIN_MIN = 0.0;
	public const DISTRIBUTION_GRUND_MIN_MAX = 99.0;
	public const DISTRIBUTION_GRUND_MIN_STD = 1.0;

	public const DISTRIBUTION_GRUND_MAX_MIN = 0.1;
	public const DISTRIBUTION_GRUND_MAX_MAX = 100.0;
	public const DISTRIBUTION_GRUND_MAX_STD = 50.0;

	/**
	 * @var float[] FT_SUMMAINTERVALL
	 */
	public const DISTRIBUTION_GRÄNSER = [
		0.05, 0.1, 0.2, 0.5, 1.0, 1.5, 2.0, 3.0, 4.0, 5.0, 6.0, 7.0, 8.0, 9.0, 10.0,
		15.0, 20.0, 25.0, 30.0, 35.0, 40.0, 45.0, 50.0, 60.0, 70.0, 80.0, 90.0
	];
}
