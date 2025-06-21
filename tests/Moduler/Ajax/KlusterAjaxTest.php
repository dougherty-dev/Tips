<?php

/**
 * Klass KlusterAjaxTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Moduler\Ajax;

use PHPUnit\Framework\TestCase;
use Tips\Moduler\Ajax\KlusterAjax;
use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\DBPreferenser;

/**
 * Klass KlusterAjaxTest.
 */
class KlusterAjaxTest extends TestCase
{
	/**
	 * Testa ajaxanrop för att ändra gränser för minantal.
	 */
	public function testKlusterAjaxAntal(): void
	{
		new Preludium();

		$_REQUEST['min_antal'] = '1000000';
		$this->assertInstanceOf("\Tips\Moduler\Ajax\KlusterAjax", new KlusterAjax());

		$spel = new Spel();
		$dbprefs = new DBPreferenser($spel->db);
		$min_antal = $dbprefs->hämta_preferens('kluster.min_antal');
		$this->assertNotEquals($min_antal, 1000000);
		unset($_REQUEST['min_antal']);

		$_REQUEST['min_antal'] = '1';
		new KlusterAjax();

		$spel = new Spel();
		$dbprefs = new DBPreferenser($spel->db);
		$min_antal = $dbprefs->hämta_preferens('kluster.min_antal');
		$this->assertEquals($min_antal, 1);
		unset($_REQUEST['min_antal']);
	}

	/**
	 * Testa ajaxanrop för att ändra gränser för radie.
	 */
	public function testKlusterAjaxRadie(): void
	{
		new Preludium();

		$_REQUEST['min_radie'] = '1000';
		new KlusterAjax();

		$spel = new Spel();
		$dbprefs = new DBPreferenser($spel->db);
		$min_radie = $dbprefs->hämta_preferens('kluster.min_radie');
		$this->assertNotEquals($min_radie, 1000);
		unset($_REQUEST['min_radie']);

		$_REQUEST['min_radie'] = '45';
		new KlusterAjax();

		$spel = new Spel();
		$dbprefs = new DBPreferenser($spel->db);
		$min_radie = $dbprefs->hämta_preferens('kluster.min_radie');
		$this->assertEquals($min_radie, 45);
		unset($_REQUEST['min_radie']);

		$_REQUEST['attraktionsfaktor'] = '1594322';
		new KlusterAjax();
		unset($_REQUEST['attraktionsfaktor']);
	}
}
