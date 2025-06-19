<?php

/**
 * Klass SekvensAjaxTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Ajax;

use PHPUnit\Framework\TestCase;
use Tips\Klasser\Spel;
use Tips\Ajax\SpelAjax;
use Tips\Ajax\SekvensAjax;
use Tips\Klasser\Utdelning;
use Tips\Klasser\Preludium;
use Tips\Klasser\Tips;
use Tips\Moduler\TT;

/**
 * Klass SekvensAjaxTest.
 */
class SekvensAjaxTest extends TestCase
{
	/**
	 * Construct object with argument and verify that the object has the expected properties.
	 */
	public function testSekvensAjax(): void
	{
		new Preludium();

		/**
		 * Preparera.
		 */
		$_REQUEST['ändra_speltyp'] = '1';
		new SpelAjax();
		unset($_REQUEST['ändra_speltyp']);

		$_REQUEST['ändra_omgång'] = '4905';
		new SpelAjax();
		unset($_REQUEST['ändra_omgång']);

		$_REQUEST['ny_sekvens'] = true;
		new SpelAjax();
		$spel = new Spel();
		$this->assertEquals($spel->sekvens, 2);
		unset($_REQUEST['ny_sekvens']);

		$_REQUEST['ändra_sekvens'] = '1';
		new SpelAjax();
		$spel = new Spel();
		$this->assertEquals($spel->sekvens, 1);
		unset($_REQUEST['ändra_sekvens']);

		$_REQUEST['ny_sekvens'] = 'sträng';
		new SpelAjax();
		$spel = new Spel();
		$this->assertEquals($spel->sekvens, 1);
		unset($_REQUEST['ny_sekvens']);

		$_REQUEST['radera_sekvens'] = '2';
		$_REQUEST['omgång'] = '4905';
		$_REQUEST['speltyp'] = '1';
		new SekvensAjax();
		$spel = new Spel();
		$this->assertEquals($spel->sekvens, 1);
		unset($_REQUEST['radera_sekvens']);
		unset($_REQUEST['omgång']);
		unset($_REQUEST['speltyp']);
	}
}
