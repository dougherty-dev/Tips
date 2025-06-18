<?php

/**
 * Klass R_3_4_13_2.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Koder\R2;

use ReflectionClass;

/**
 * Klass R_3_4_13_2.
 */
class R_3_4_13_2 {
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
