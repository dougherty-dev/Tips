<?php

/**
 * Klass R_13_0_59049_1.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Koder\R1;

use ReflectionClass;

/**
 * Klass R_13_0_59049_1.
 * Hamming, R. 1948.
 * Perfekt hammingkod.
 * Garanti: 3.7 %.
 * GAP: HammingCode(3, GF(3))
 * GAP/Guava: a linear [13,10,3]1 Hamming (3,3) code over GF(3).
 * IsCyclicCode: false
 * IsSelfOrthogonalCode: false
 * IsPerfectCode: true
 * Size: 59049
 * WordLength: 13
 * MinimumDistance: 3
 * Redundancy: 3
 * WeightDistribution: [ 1, 0, 0, 104, 468, 1404, 4056, 8424, 11934, 13442, 11232, 5616, 2080, 288 ]
 */
class R_13_0_59049_1 {
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
