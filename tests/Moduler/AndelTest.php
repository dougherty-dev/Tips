<?php

/**
 * Klass AndelTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Moduler;

use PHPUnit\Framework\TestCase;
use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\Tips;
use Tips\Moduler\Andel;

/**
 * Klass AndelTest.
 */
class AndelTest extends TestCase
{
	/**
	 * Testa Andel med metoder.
	 */
	public function testAndel(): void
	{
		new Preludium();
		$spel = new Spel();
		$tips = new Tips($spel);
		$andel = new Andel($tips->utdelning, $tips->odds, $tips->streck, $tips->matcher);
		$this->assertInstanceOf("\Tips\Moduler\Andel", $andel);

		$andel->visa_modul();
		$this->expectOutputRegex('*Andel*');
	}
}
