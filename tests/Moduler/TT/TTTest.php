<?php

/**
 * Klass TTTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Moduler\TT;

use PHPUnit\Framework\TestCase;
use Tips\Moduler\TT;
use Tips\Klasser\Utdelning;
use Tips\Klasser\Spel;
use Tips\Klasser\Preludium;
use Tips\Klasser\Tips;
use Tips\Klasser\Prediktioner;
use Tips\Moduler\TT\TTGridGenerera\Generera;
use Tips\Moduler\TT\Visa;
use Tips\Klasser\Databas\DB;

/**
 * Klass TTTest.
 */
class TTTest extends TestCase
{
	/**
	 * Construct object with argument and verify that the object has the expected properties.
	 */
	public function testCreateObject(): void
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
		$tt_ajax = new Generera($tt);
		$this->assertInstanceOf("\Tips\Moduler\TT\TTGridGenerera\Generera", $tt_ajax);
		unset($_REQUEST['generera_topptips']);
	}
}
