<?php

/**
 * Klass R_11_0_9477_1.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Koder\R1;

use ReflectionClass;

/**
 * Klass R_11_0_9477_1.
 * Östergård, P. 1992.
 * 6:1:9477: ['11100', '10011', '21010', '20101', '01111', '01221'], '01112', '10101', '20011',
 * '01121', '11010', '21100', '01211', '10220', '20220', '02111', '12002', '22002', '02222'
 */
class R_11_0_9477_1 {
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
