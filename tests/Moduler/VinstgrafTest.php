<?php

/**
 * Klass VinstgrafTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Moduler;

use PHPUnit\Framework\TestCase;
use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\Tips;
use Tips\Moduler\Vinstgraf;

/**
 * Klass VinstgrafTest.
 */
class VinstgrafTest extends TestCase
{
	/**
	 * Construct object with argument and verify that the object has the expected properties.
	 */
	public function testCreateObject(): void
	{
		new Preludium();
		$spel = new Spel();
		$tips = new Tips($spel);
		unlink(GRAF . "/vinstgraf.png");
		$vinstgraf = new Vinstgraf($tips->utdelning, $tips->odds, $tips->streck, $tips->matcher);
		$this->assertInstanceOf("\Tips\Moduler\Vinstgraf", $vinstgraf);

		$vinstgraf->visa_modul();
		$this->expectOutputRegex('*Vinstgraf*');
	}
}
