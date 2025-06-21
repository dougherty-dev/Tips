<?php

/**
 * Klass VinstgrafAjaxTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Moduler\Ajax;

use PHPUnit\Framework\TestCase;
use Tips\Moduler\Ajax\VinstgrafAjax;
use Tips\Moduler\Vinstgraf;
use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\Tips;

/**
 * Klass VinstgrafAjaxTest.
 */
class VinstgrafAjaxTest extends TestCase
{
	/**
	 * Construct object with argument and verify that the object has the expected properties.
	 */
	public function testVinstgrafAjax(): void
	{
		new Preludium();

		$_REQUEST['utdelning_13_min'] = '50';
		$_REQUEST['utdelning_13_max'] = '50000';
		$this->assertInstanceOf("\Tips\Moduler\Ajax\VinstgrafAjax", new VinstgrafAjax());

		$spel = new Spel();
		$tips = new Tips($spel);

		$vinstgraf = new Vinstgraf($tips->utdelning, $tips->odds, $tips->streck, $tips->matcher);
		$this->assertInstanceOf("\Tips\Moduler\Vinstgraf", $vinstgraf);

		$this->assertEquals($vinstgraf->utdelning_13_min, 10000);
		unset($_REQUEST['utdelning_13_min'], $_REQUEST['utdelning_13_max']);


		$_REQUEST['utdelning_13_min'] = '150';
		$_REQUEST['utdelning_13_max'] = '50000';
		new VinstgrafAjax();

		$spel = new Spel();
		$tips = new Tips($spel);
		$vinstgraf = new Vinstgraf($tips->utdelning, $tips->odds, $tips->streck, $tips->matcher);
		$this->assertEquals($vinstgraf->utdelning_13_min, 150);

		unset($_REQUEST['utdelning_13_min'], $_REQUEST['utdelning_13_max']);
	}
}
