<?php

/**
 * Klass R_10_2_13122_1.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Koder\R1;

use ReflectionClass;

/**
 * Klass R_10_2_13122_1.
 * Hämäläinen, H.; Östergård, P. 1993.
 * 5:2:13122: [160, 179, 127, 219, 79], 5, 26, 28, 0, 0, 60, 1, 0, 65, 20, 0, 42, 0,
 * 4, 31, 0, 82, 4, 0, 0, 25, 19, 14, 1, 0, 26, 0, 84, 55, 0, 20, 0, 18, 0, 20, 26,
 * 0, 17, 0, 12, 29, 2, 7, 23, 6, 15, 41, 0, 5, 51, 4, 0, 18, 0]
 */
class R_10_2_13122_1 {
	/**
	 * @var string[]
	 */
	public array $kod;

	/**
	 * Initiera.
	 */
	public function __construct() {
		$klass = (new ReflectionClass($this))->getShortName();
		if ($fil = file(__DIR__ . "/$klass.txt", FILE_IGNORE_NEW_LINES)) {
			$this->kod = $fil;
		}
	}
}
