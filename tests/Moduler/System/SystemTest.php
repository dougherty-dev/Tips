<?php

/**
 * Klass SystemTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Moduler\System;

use PHPUnit\Framework\TestCase;
use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\Tips;
use Tips\Moduler\System;

/**
 * Klass SystemTest.
 */
class SystemTest extends TestCase
{
	/**
	 * Construct object with argument and verify that the object has the expected properties.
	 */
	public function testCreateObject(): void
	{
		new Preludium();
		$spel = new Spel();
		$tips = new Tips($spel);
		$system = new System($tips->utdelning, $tips->odds, $tips->streck, $tips->matcher);
		$this->assertInstanceOf("\Tips\Moduler\System", $system);

		$system->visa_modul();
		$this->expectOutputRegex('*Kodanalys*');
	}
}
