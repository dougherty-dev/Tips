<?php

/**
 * Klass HGAjaxTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Moduler\Ajax;

use PHPUnit\Framework\TestCase;
use Tips\Moduler\Ajax\HGAjax;
use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\DBPreferenser;

/**
 * Klass HGAjaxTest.
 */
class HGAjaxTest extends TestCase
{
	/**
	 * Testa ajaxanrop för att ändra gränser för minantal.
	 */
	public function testHGAjaxMin(): void
	{
		new Preludium();

		$_REQUEST['hg_min'] = '1000000';
		$this->assertInstanceOf("\Tips\Moduler\Ajax\HGAjax", new HGAjax());

		$spel = new Spel();
		$dbprefs = new DBPreferenser($spel->db);
		$hg_min = $dbprefs->hämta_preferens('hg.hg_min');
		$this->assertNotEquals($hg_min, 1000000);
		unset($_REQUEST['hg_min']);

		$_REQUEST['hg_min'] = '11';
		new HGAjax();

		$spel = new Spel();
		$dbprefs = new DBPreferenser($spel->db);
		$hg_min = $dbprefs->hämta_preferens('hg.hg_min');
		$this->assertEquals($hg_min, 11);
		unset($_REQUEST['hg_min']);

		$_REQUEST['attraktionsfaktor'] = '1594322';
		new HGAjax();
		unset($_REQUEST['attraktionsfaktor']);
	}
}
