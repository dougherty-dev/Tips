<?php

/**
 * Egenskap Konstanter.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\HG;

/**
 * Egenskap Konstanter.
 */
trait Konstanter {
	public const HG_MIN = 8;
	public const HG_MAX = MATCHRYMD;
	public const HG_STD = 10;

	/**
	 * @var array<int, array<int, float|int>> HG_MATRIS
	 */
	public const array HG_MATRIS = [
		[1594323, 100],
		[1594322, 99.999937277453],
		[1594296, 99.998306491219],
		[1593984, 99.978737056418],
		[1591696, 99.835227867879],
		[1580256, 99.117681925181],
		[1539072, 96.534516531468],
		[1429248, 89.646075481568],
		[1209600, 75.869193381768],
		[880128, 55.203870232067],
		[514048, 32.242400065733],
		[221184, 13.873223932666],
		[61440, 3.8536733146295],
		[8192, 0.51382310861726],
	];
}
