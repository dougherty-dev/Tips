<?php

/**
 * Klass VinstspridningTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Klasser;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Tips\Klasser\Vinstspridning;
use Tips\Klasser\Spel;
use Tips\Klasser\Tips;
use Tips\Klasser\Preludium;

/**
 * Klass VinstspridningTest.
 */
class VinstspridningTest extends TestCase
{
	/**
	 * Construct object with argument and verify that the object has the expected properties.
	 */
	public function testCreateObject(): void
	{
		new Preludium();
		$spel = new Spel();
		$tips = new Tips($spel);
		$vinst = new Vinstspridning($tips->utdelning->tipsrad_012);
		$this->assertInstanceOf("\Tips\Klasser\Vinstspridning", $vinst);
		unlink(GRAF . '/vinstspridning/' . $tips->utdelning->tipsrad_012 . '.png');
		$vinst->visa_vinstspridning();
	}
}
