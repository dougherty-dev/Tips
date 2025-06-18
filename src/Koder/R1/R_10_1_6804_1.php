<?php

/**
 * Klass R_10_1_6804_1.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Koder\R1;

use ReflectionClass;

/**
 * Klass R_10_1_6804_1.
 * Bertolo, R.; Di Pasquale, F.; Santisi, F. 1999.
 * 5:1:6804: [80, 89, 63, 109, 39], 12, 23, 13, 5, 26, 3, 11, 2, 9, 0, 21,
 * 25, 21, 12, 14, 11, 18, 31, 0, 36, 20, 36, 2, 23, 6, 31, 0, 24
 */
class R_10_1_6804_1 {
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
