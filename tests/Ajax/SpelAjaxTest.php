<?php

/**
 * Klass SpelAjaxTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Ajax;

use PHPUnit\Framework\TestCase;
use Tips\Klasser\Spel;
use Tips\Ajax\SpelAjax;
use Tips\Klasser\Utdelning;
use Tips\Klasser\Preludium;
use Tips\Klasser\Tips;
use Tips\Moduler\TT;

/**
 * Klass SpelAjaxTest.
 */
class SpelAjaxTest extends TestCase
{
	/**
	 * Construct object with argument and verify that the object has the expected properties.
	 */
	public function testSpelAjax(): void
	{
		new Preludium();

		/**
		 * Preparera.
		 */
		$_REQUEST['ändra_speltyp'] = '1';
		new SpelAjax();
		unset($_REQUEST['ändra_speltyp']);

		$_REQUEST['ändra_omgång'] = '4905';
		$spelajax = new SpelAjax();
		$spel = new Spel();
		$this->assertInstanceOf("\Tips\Ajax\SpelAjax", $spelajax);
		$this->assertInstanceOf("\Tips\Klasser\Spel", $spel);
		$this->assertEquals($spel->speltyp->value, 1);
		unset($_REQUEST['ändra_omgång']);

		// Ändra till Europatips
		$_REQUEST['ändra_speltyp'] = '2';
		new SpelAjax();
		$spel = new Spel();
		$this->assertEquals($spel->speltyp->value, 2);
		unset($_REQUEST['ändra_speltyp']);

		// Ändra till Stryktips
		$_REQUEST['ändra_speltyp'] = '1';
		new SpelAjax();
		$spel = new Spel();
		$this->assertEquals($spel->speltyp->value, 1);
		unset($_REQUEST['ändra_speltyp']);

		$utd = new Utdelning($spel);
		$utd->hämta_utdelning();
		$this->assertEquals($utd->utdelning[0], 21112);

		$_REQUEST['ändra_omgång'] = '0';
		new SpelAjax();
		$spel = new Spel();
		$this->assertEquals($spel->omgång, 4905);
		unset($_REQUEST['ändra_omgång']);
	}
}
