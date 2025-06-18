<?php

/**
 * Klass BCH_8_6_2_3
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Koder;

use ReflectionClass;

/**
 * Klass BCH_8_6_2_3.
 */
class BCH_8_6_2_3 {
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
