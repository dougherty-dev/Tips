<?php

/**
 * Klass TTTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Moduler\TT;

use PHPUnit\Framework\TestCase;
use Tips\Moduler\TT;
use Tips\Klasser\Spel;
use Tips\Klasser\Preludium;
use Tips\Klasser\Tips;
use Tips\Moduler\TT\TTGridGenerera\Generera;
use Tips\Moduler\TT\Visa;

/**
 * Klass TTTest.
 */
class TTTest extends TestCase
{
	/**
	 * Tester för Topptipset.
	 */
	public function testTopptipset(): void
	{
		new Preludium();
		$spel = new Spel();
		$tips = new Tips($spel);
		$tt = new TT($tips->utdelning, $tips->odds, $tips->streck, $tips->matcher);
		$this->assertInstanceOf("\Tips\Moduler\TT", $tt);

		$visa = new Visa($tt);
		$visa->visa();
		$this->expectOutputRegex('*Kodanalys*');

		$_REQUEST['generera_topptips'] = true;
		$this->assertInstanceOf("\Tips\Moduler\TT\TTGridGenerera\Generera", new Generera($tt));
		unset($_REQUEST['generera_topptips']);
	}
}
