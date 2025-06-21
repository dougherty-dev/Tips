<?php

/**
 * Klass AndelAjaxTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Moduler\Ajax;

use PHPUnit\Framework\TestCase;
use Tips\Moduler\Ajax\AndelAjax;
use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\DBPreferenser;

/**
 * Klass AndelAjaxTest.
 */
class AndelAjaxTest extends TestCase
{
	/**
	 * Testa ajaxanrop för att ändra gränser för intervall av ettor.
	 */
	public function testAndelAjax1(): void
	{
		new Preludium();

		$_REQUEST['andel_1_min'] = '14';
		$_REQUEST['andel_1_max'] = '15';
		$this->assertInstanceOf("\Tips\Moduler\Ajax\AndelAjax", new AndelAjax());

		$spel = new Spel();
		$dbprefs = new DBPreferenser($spel->db);
		$andel_1_min = $dbprefs->hämta_preferens('andel.andel_1_min');
		$andel_1_max = $dbprefs->hämta_preferens('andel.andel_1_max');
		$this->assertEquals($andel_1_min, 3);
		$this->assertEquals($andel_1_max, 8);
		unset($_REQUEST['andel_1_min'], $_REQUEST['andel_1_max']);
	}

	/**
	 * Testa ajaxanrop för att ändra gränser för intervall av kryss.
	 */
	public function testAndelAjaxX(): void
	{
		new Preludium();

		$_REQUEST['andel_x_min'] = '14';
		$_REQUEST['andel_x_max'] = '15';
		new AndelAjax();

		$spel = new Spel();
		$dbprefs = new DBPreferenser($spel->db);
		$andel_x_min = $dbprefs->hämta_preferens('andel.andel_x_min');
		$andel_x_max = $dbprefs->hämta_preferens('andel.andel_x_max');
		$this->assertEquals($andel_x_min, 1);
		$this->assertEquals($andel_x_max, 6);
		unset($_REQUEST['andel_x_min'], $_REQUEST['andel_x_max']);
	}

	/**
	 * Testa ajaxanrop för att ändra gränser för intervall av kryss.
	 */
	public function testAndelAjax2(): void
	{
		new Preludium();

		$_REQUEST['andel_2_min'] = '14';
		$_REQUEST['andel_2_max'] = '15';
		new AndelAjax();

		$spel = new Spel();
		$dbprefs = new DBPreferenser($spel->db);
		$andel_2_min = $dbprefs->hämta_preferens('andel.andel_2_min');
		$andel_2_max = $dbprefs->hämta_preferens('andel.andel_2_max');
		$this->assertEquals($andel_2_min, 2);
		$this->assertEquals($andel_2_max, 7);
		unset($_REQUEST['andel_2_min'], $_REQUEST['andel_2_max']);

		$_REQUEST['attraktionsfaktor'] = '1594322';
		new AndelAjax();
		unset($_REQUEST['attraktionsfaktor']);
	}
}
