<?php

/**
 * Klass DistributionTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Moduler;

use PHPUnit\Framework\TestCase;
use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\Tips;
use Tips\Moduler\Distribution;

/**
 * Klass DistributionTest.
 */
class DistributionTest extends TestCase
{
	/**
	 * Testa Distribution med metoder.
	 */
	public function testDistribution(): void
	{
		new Preludium();
		$spel = new Spel();
		$tips = new Tips($spel);
		$dist = new Distribution($tips->utdelning, $tips->odds, $tips->streck, $tips->matcher);
		$this->assertInstanceOf("\Tips\Moduler\Distribution", $dist);

		$dist->visa_modul();
		$this->expectOutputRegex('*Andelssumma*');
	}
}
