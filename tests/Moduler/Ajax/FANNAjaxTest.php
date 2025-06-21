<?php

/**
 * Klass FANNAjaxTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Moduler\Ajax;

use PHPUnit\Framework\TestCase;
use Tips\Moduler\Ajax\FANNAjax;
use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\DBPreferenser;

/**
 * Klass FANNAjaxTest.
 */
class FANNAjaxTest extends TestCase
{
	/**
	 * Testa ajaxanrop för att ändra gränser för minantal.
	 */
	public function testFannAjaxMin(): void
	{
		new Preludium();

		$_REQUEST['fann_min'] = '1000000';
		$this->assertInstanceOf("\Tips\Moduler\Ajax\FANNAjax", new FANNAjax());

		$spel = new Spel();
		$dbprefs = new DBPreferenser($spel->db);
		$fann_min = $dbprefs->hämta_preferens('fann.fann_min');
		$this->assertNotEquals($fann_min, 1000000);
		unset($_REQUEST['fann_min']);

		$_REQUEST['fann_min'] = '11';
		new FANNAjax();

		$spel = new Spel();
		$dbprefs = new DBPreferenser($spel->db);
		$fann_min = $dbprefs->hämta_preferens('fann.fann_min');
		$this->assertEquals($fann_min, 11);
		unset($_REQUEST['fann_min']);

		$_REQUEST['attraktionsfaktor'] = '1594322';
		new FANNAjax();
		unset($_REQUEST['attraktionsfaktor']);
	}

	/**
	 * Testa ajaxanrop för att ändra gränser för feltolerans.
	 */
	public function testFannAjaxTolerans(): void
	{
		new Preludium();

		$_REQUEST['fann_feltolerans'] = '1.1';

		$spel = new Spel();
		$dbprefs = new DBPreferenser($spel->db);
		$fann_feltolerans = $dbprefs->hämta_preferens('fann.fann_feltolerans');
		$this->assertNotEquals($fann_feltolerans, 1.1);
		unset($_REQUEST['fann_feltolerans']);

		$_REQUEST['fann_feltolerans'] = '0.13';
		new FANNAjax();

		$spel = new Spel();
		$dbprefs = new DBPreferenser($spel->db);
		$fann_feltolerans = $dbprefs->hämta_preferens('fann.fann_feltolerans');
		$this->assertEquals($fann_feltolerans, 0.13);
		unset($_REQUEST['fann_feltolerans']);
	}
}
