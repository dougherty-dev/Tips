<?php

/**
 * Klass OmgangAjaxTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Ajax;

use PHPUnit\Framework\TestCase;
use Tips\Ajax\OmgangAjax;
use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\DBPreferenser;

/**
 * Klass OmgangAjaxTest.
 */
class OmgangAjaxTest extends TestCase
{
	/**
	 * Construct object with argument and verify that the object has the expected properties.
	 */
	public function testOmgangAjax(): void
	{
		new Preludium();

		$_REQUEST['investera_sparad'] = true;
		$omgangajax = new OmgangAjax();
		$this->assertInstanceOf("\Tips\Ajax\OmgangAjax", $omgangajax);
		unset($_REQUEST['investera_sparad']);

		$_REQUEST['strategi'] = 'Min strategi';
		new OmgangAjax();
		unset($_REQUEST['strategi']);
		$spel = new Spel();
		$strategi = (new DBPreferenser($spel->db))->hämta_preferens('strategi');
		$this->assertEquals($strategi, 'Min strategi');

		$_REQUEST['radera_omgång'] = '4314';
		$_REQUEST['speltyp'] = '1';
		new OmgangAjax();
		unset($_REQUEST['radera_omgång'], $_REQUEST['speltyp']);

		$_REQUEST['ändra_omgång'] = '4314';
		new OmgangAjax();
		$spel = new Spel();
		$this->assertNotEquals($spel->omgång, 4314);
		unset($_REQUEST['ändra_omgång']);
	}
}
