<?php

/**
 * Klass AutospikTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Moduler;

use PHPUnit\Framework\TestCase;
use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\Tips;
use Tips\Moduler\Autospik;

/**
 * Klass AutospikTest.
 */
class AutospikTest extends TestCase
{
	/**
	 * Testa Autospik med metoder.
	 */
	public function testAutospik(): void
	{
		new Preludium();
		$spel = new Spel();
		$tips = new Tips($spel);
		$autospik = new Autospik($tips->utdelning, $tips->odds, $tips->streck, $tips->matcher);
		$this->assertInstanceOf("\Tips\Moduler\Autospik", $autospik);

		$autospik->visa_modul();
		$this->expectOutputRegex('*matcher*');
	}
}
