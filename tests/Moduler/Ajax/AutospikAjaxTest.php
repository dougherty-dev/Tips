<?php

/**
 * Klass AutospikAjaxTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Moduler\Ajax;

use PHPUnit\Framework\TestCase;
use Tips\Moduler\Ajax\AutospikAjax;
use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\DBPreferenser;

/**
 * Klass AutospikAjaxTest.
 */
class AutospikAjaxTest extends TestCase
{
	/**
	 * Testa ajaxanrop för att ändra gränser för antal.
	 */
	public function testAutospikAjax(): void
	{
		new Preludium();

		$_REQUEST['valda_spikar'] = '1000000';
		$this->assertInstanceOf("\Tips\Moduler\Ajax\AutospikAjax", new AutospikAjax());

		$spel = new Spel();
		$dbprefs = new DBPreferenser($spel->db);
		$valda_spikar = $dbprefs->hämta_preferens('autospik.valda_spikar');
		$this->assertNotEquals($valda_spikar, 1000000);
		unset($_REQUEST['valda_spikar']);

		$_REQUEST['valda_spikar'] = '4';
		new AutospikAjax();

		$spel = new Spel();
		$dbprefs = new DBPreferenser($spel->db);
		$valda_spikar = $dbprefs->hämta_preferens('autospik.valda_spikar');
		$this->assertEquals($valda_spikar, 4);
		unset($_REQUEST['valda_spikar']);

		$_REQUEST['attraktionsfaktor'] = '1594322';
		new AutospikAjax();
		unset($_REQUEST['attraktionsfaktor']);
	}
}
