<?php

/**
 * Klass R_12_1_52488_1.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Koder\R1;

use ReflectionClass;

/**
 * Klass R_12_1_52488_1.
 * Hämäläinen, H.; Östergård, P. 1993.
 * 6:1:52488 [8, 207, 287, 35, 107, 539], 20, 14, 18, 10, 3, 54,
 * 0, 21, 2, 13, 4, 84, 14, 19, 8, 3, 64, 8, 23, 4, 2, 28, 2, 4,
 * 41, 8, 3, 80, 2, 4, 25, 2, 65, 10, 3, 22, 14, 22, 4, 2, 66, 8,
 * 3, 18, 2, 89, 10, 40, 4, 2, 26, 12, 21, 4, 69, 2, 4, 25, 12,
 * 7, 0, 71, 8, 7, 10, 21, 51, 8, 3, 21, 10, 3]
 */
class R_12_1_52488_1 {
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
