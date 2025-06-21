<?php

/**
 * Klass SystemAjaxTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Moduler\Ajax;

use PHPUnit\Framework\TestCase;
use Tips\Moduler\Ajax\SystemAjax;
use Tips\Moduler\System;
use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\Tips;
use Tips\Klasser\DBPreferenser;

/**
 * Klass SystemAjaxTest.
 */
class SystemAjaxTest extends TestCase
{
	/**
	 * Tester för SystemAjax med metoder.
	 */
	public function testSystemAjax(): void
	{
		new Preludium();

		$_REQUEST['reduktion'] = 'reduktion%5B0%5D%5B0%5D=1&reduktion%5B0%5D%5B1%5D=X&reduktion%5B0%5D%5B2%5D=2&reduktion%5B1%5D%5B0%5D=1&reduktion%5B1%5D%5B1%5D=X&reduktion%5B1%5D%5B2%5D=2&reduktion%5B2%5D%5B0%5D=1&reduktion%5B2%5D%5B1%5D=X&reduktion%5B2%5D%5B2%5D=2&reduktion%5B3%5D%5B0%5D=1&reduktion%5B3%5D%5B1%5D=X&reduktion%5B3%5D%5B2%5D=2&reduktion%5B4%5D%5B0%5D=&reduktion%5B4%5D%5B1%5D=&reduktion%5B4%5D%5B2%5D=&reduktion%5B5%5D%5B0%5D=1&reduktion%5B5%5D%5B1%5D=X&reduktion%5B5%5D%5B2%5D=2&reduktion%5B6%5D%5B0%5D=1&reduktion%5B6%5D%5B1%5D=X&reduktion%5B6%5D%5B2%5D=2&reduktion%5B7%5D%5B0%5D=&reduktion%5B7%5D%5B1%5D=&reduktion%5B7%5D%5B2%5D=&reduktion%5B8%5D%5B0%5D=&reduktion%5B8%5D%5B1%5D=&reduktion%5B8%5D%5B2%5D=&reduktion%5B9%5D%5B0%5D=&reduktion%5B9%5D%5B1%5D=&reduktion%5B9%5D%5B2%5D=&reduktion%5B10%5D%5B0%5D=&reduktion%5B10%5D%5B1%5D=&reduktion%5B10%5D%5B2%5D=&reduktion%5B11%5D%5B0%5D=1&reduktion%5B11%5D%5B1%5D=X&reduktion%5B11%5D%5B2%5D=2&reduktion%5B12%5D%5B0%5D=&reduktion%5B12%5D%5B1%5D=&reduktion%5B12%5D%5B2%5D=';
		$this->expectOutputString('11');
		$this->assertInstanceOf("\Tips\Moduler\Ajax\SystemAjax", new SystemAjax());

		$reduktion = '1,X,2,1,X,2,1,X,2,1,X,2,,,,1,X,2,1,X,2,,,,,,,,,,,,,1,X,2,,,';

		$spel = new Spel();
		$dbprefs = new DBPreferenser($spel->db);
		$red = $dbprefs->hämta_preferens('system.reduktion');
		$this->assertEquals($reduktion, $red);
		unset($_REQUEST['reduktion']);

		$_REQUEST['kod'] = 'R_6_1_132_1';
		new SystemAjax();
		$this->expectOutputRegex('*Reducerad*');
		unset($_REQUEST['kod']);

		$_REQUEST['status'] = 'teststatus';
		$_REQUEST['id'] = 'inget';
		new SystemAjax();
		unset($_REQUEST['status'], $_REQUEST['id']);

		$_REQUEST['attraktionsfaktor'] = '1594322';
		new SystemAjax();
		unset($_REQUEST['attraktionsfaktor']);


		$_REQUEST['garderingar'] = 'garderingar%5B0%5D%5B0%5D%5B0%5D=&garderingar%5B0%5D%5B0%5D%5B1%5D=&garderingar%5B0%5D%5B0%5D%5B2%5D=&garderingar%5B1%5D%5B0%5D%5B0%5D=&garderingar%5B1%5D%5B0%5D%5B1%5D=&garderingar%5B1%5D%5B0%5D%5B2%5D=&garderingar%5B2%5D%5B0%5D%5B0%5D=&garderingar%5B2%5D%5B0%5D%5B1%5D=&garderingar%5B2%5D%5B0%5D%5B2%5D=&garderingar%5B3%5D%5B0%5D%5B0%5D=&garderingar%5B3%5D%5B0%5D%5B1%5D=&garderingar%5B3%5D%5B0%5D%5B2%5D=&garderingar%5B4%5D%5B0%5D%5B0%5D=&garderingar%5B4%5D%5B0%5D%5B1%5D=&garderingar%5B4%5D%5B0%5D%5B2%5D=&garderingar%5B5%5D%5B0%5D%5B0%5D=&garderingar%5B5%5D%5B0%5D%5B1%5D=&garderingar%5B5%5D%5B0%5D%5B2%5D=&garderingar%5B6%5D%5B0%5D%5B0%5D=&garderingar%5B6%5D%5B0%5D%5B1%5D=&garderingar%5B6%5D%5B0%5D%5B2%5D=&garderingar%5B7%5D%5B0%5D%5B0%5D=&garderingar%5B7%5D%5B0%5D%5B1%5D=&garderingar%5B7%5D%5B0%5D%5B2%5D=&garderingar%5B0%5D%5B1%5D%5B0%5D=&garderingar%5B0%5D%5B1%5D%5B1%5D=&garderingar%5B0%5D%5B1%5D%5B2%5D=&garderingar%5B1%5D%5B1%5D%5B0%5D=&garderingar%5B1%5D%5B1%5D%5B1%5D=&garderingar%5B1%5D%5B1%5D%5B2%5D=&garderingar%5B2%5D%5B1%5D%5B0%5D=&garderingar%5B2%5D%5B1%5D%5B1%5D=&garderingar%5B2%5D%5B1%5D%5B2%5D=&garderingar%5B3%5D%5B1%5D%5B0%5D=&garderingar%5B3%5D%5B1%5D%5B1%5D=&garderingar%5B3%5D%5B1%5D%5B2%5D=&garderingar%5B4%5D%5B1%5D%5B0%5D=&garderingar%5B4%5D%5B1%5D%5B1%5D=&garderingar%5B4%5D%5B1%5D%5B2%5D=&garderingar%5B5%5D%5B1%5D%5B0%5D=&garderingar%5B5%5D%5B1%5D%5B1%5D=&garderingar%5B5%5D%5B1%5D%5B2%5D=&garderingar%5B6%5D%5B1%5D%5B0%5D=&garderingar%5B6%5D%5B1%5D%5B1%5D=&garderingar%5B6%5D%5B1%5D%5B2%5D=&garderingar%5B7%5D%5B1%5D%5B0%5D=&garderingar%5B7%5D%5B1%5D%5B1%5D=&garderingar%5B7%5D%5B1%5D%5B2%5D=&garderingar%5B0%5D%5B2%5D%5B0%5D=&garderingar%5B0%5D%5B2%5D%5B1%5D=&garderingar%5B0%5D%5B2%5D%5B2%5D=&garderingar%5B1%5D%5B2%5D%5B0%5D=&garderingar%5B1%5D%5B2%5D%5B1%5D=&garderingar%5B1%5D%5B2%5D%5B2%5D=&garderingar%5B2%5D%5B2%5D%5B0%5D=&garderingar%5B2%5D%5B2%5D%5B1%5D=&garderingar%5B2%5D%5B2%5D%5B2%5D=&garderingar%5B3%5D%5B2%5D%5B0%5D=&garderingar%5B3%5D%5B2%5D%5B1%5D=&garderingar%5B3%5D%5B2%5D%5B2%5D=&garderingar%5B4%5D%5B2%5D%5B0%5D=&garderingar%5B4%5D%5B2%5D%5B1%5D=&garderingar%5B4%5D%5B2%5D%5B2%5D=&garderingar%5B5%5D%5B2%5D%5B0%5D=&garderingar%5B5%5D%5B2%5D%5B1%5D=&garderingar%5B5%5D%5B2%5D%5B2%5D=&garderingar%5B6%5D%5B2%5D%5B0%5D=&garderingar%5B6%5D%5B2%5D%5B1%5D=&garderingar%5B6%5D%5B2%5D%5B2%5D=&garderingar%5B7%5D%5B2%5D%5B0%5D=&garderingar%5B7%5D%5B2%5D%5B1%5D=&garderingar%5B7%5D%5B2%5D%5B2%5D=&garderingar%5B0%5D%5B3%5D%5B0%5D=&garderingar%5B0%5D%5B3%5D%5B1%5D=&garderingar%5B0%5D%5B3%5D%5B2%5D=&garderingar%5B1%5D%5B3%5D%5B0%5D=&garderingar%5B1%5D%5B3%5D%5B1%5D=&garderingar%5B1%5D%5B3%5D%5B2%5D=&garderingar%5B2%5D%5B3%5D%5B0%5D=&garderingar%5B2%5D%5B3%5D%5B1%5D=&garderingar%5B2%5D%5B3%5D%5B2%5D=&garderingar%5B3%5D%5B3%5D%5B0%5D=&garderingar%5B3%5D%5B3%5D%5B1%5D=&garderingar%5B3%5D%5B3%5D%5B2%5D=&garderingar%5B4%5D%5B3%5D%5B0%5D=&garderingar%5B4%5D%5B3%5D%5B1%5D=&garderingar%5B4%5D%5B3%5D%5B2%5D=&garderingar%5B5%5D%5B3%5D%5B0%5D=&garderingar%5B5%5D%5B3%5D%5B1%5D=&garderingar%5B5%5D%5B3%5D%5B2%5D=&garderingar%5B6%5D%5B3%5D%5B0%5D=&garderingar%5B6%5D%5B3%5D%5B1%5D=&garderingar%5B6%5D%5B3%5D%5B2%5D=&garderingar%5B7%5D%5B3%5D%5B0%5D=&garderingar%5B7%5D%5B3%5D%5B1%5D=&garderingar%5B7%5D%5B3%5D%5B2%5D=&garderingar%5B0%5D%5B4%5D%5B0%5D=&garderingar%5B0%5D%5B4%5D%5B1%5D=&garderingar%5B0%5D%5B4%5D%5B2%5D=&garderingar%5B1%5D%5B4%5D%5B0%5D=&garderingar%5B1%5D%5B4%5D%5B1%5D=&garderingar%5B1%5D%5B4%5D%5B2%5D=&garderingar%5B2%5D%5B4%5D%5B0%5D=&garderingar%5B2%5D%5B4%5D%5B1%5D=&garderingar%5B2%5D%5B4%5D%5B2%5D=&garderingar%5B3%5D%5B4%5D%5B0%5D=&garderingar%5B3%5D%5B4%5D%5B1%5D=&garderingar%5B3%5D%5B4%5D%5B2%5D=&garderingar%5B4%5D%5B4%5D%5B0%5D=&garderingar%5B4%5D%5B4%5D%5B1%5D=&garderingar%5B4%5D%5B4%5D%5B2%5D=&garderingar%5B5%5D%5B4%5D%5B0%5D=&garderingar%5B5%5D%5B4%5D%5B1%5D=&garderingar%5B5%5D%5B4%5D%5B2%5D=&garderingar%5B6%5D%5B4%5D%5B0%5D=&garderingar%5B6%5D%5B4%5D%5B1%5D=&garderingar%5B6%5D%5B4%5D%5B2%5D=&garderingar%5B7%5D%5B4%5D%5B0%5D=&garderingar%5B7%5D%5B4%5D%5B1%5D=&garderingar%5B7%5D%5B4%5D%5B2%5D=&garderingar%5B0%5D%5B5%5D%5B0%5D=&garderingar%5B0%5D%5B5%5D%5B1%5D=&garderingar%5B0%5D%5B5%5D%5B2%5D=&garderingar%5B1%5D%5B5%5D%5B0%5D=&garderingar%5B1%5D%5B5%5D%5B1%5D=&garderingar%5B1%5D%5B5%5D%5B2%5D=&garderingar%5B2%5D%5B5%5D%5B0%5D=&garderingar%5B2%5D%5B5%5D%5B1%5D=&garderingar%5B2%5D%5B5%5D%5B2%5D=&garderingar%5B3%5D%5B5%5D%5B0%5D=&garderingar%5B3%5D%5B5%5D%5B1%5D=&garderingar%5B3%5D%5B5%5D%5B2%5D=&garderingar%5B4%5D%5B5%5D%5B0%5D=&garderingar%5B4%5D%5B5%5D%5B1%5D=&garderingar%5B4%5D%5B5%5D%5B2%5D=&garderingar%5B5%5D%5B5%5D%5B0%5D=&garderingar%5B5%5D%5B5%5D%5B1%5D=&garderingar%5B5%5D%5B5%5D%5B2%5D=&garderingar%5B6%5D%5B5%5D%5B0%5D=&garderingar%5B6%5D%5B5%5D%5B1%5D=&garderingar%5B6%5D%5B5%5D%5B2%5D=&garderingar%5B7%5D%5B5%5D%5B0%5D=&garderingar%5B7%5D%5B5%5D%5B1%5D=&garderingar%5B7%5D%5B5%5D%5B2%5D=&garderingar%5B0%5D%5B6%5D%5B0%5D=&garderingar%5B0%5D%5B6%5D%5B1%5D=&garderingar%5B0%5D%5B6%5D%5B2%5D=&garderingar%5B1%5D%5B6%5D%5B0%5D=&garderingar%5B1%5D%5B6%5D%5B1%5D=&garderingar%5B1%5D%5B6%5D%5B2%5D=&garderingar%5B2%5D%5B6%5D%5B0%5D=&garderingar%5B2%5D%5B6%5D%5B1%5D=&garderingar%5B2%5D%5B6%5D%5B2%5D=&garderingar%5B3%5D%5B6%5D%5B0%5D=&garderingar%5B3%5D%5B6%5D%5B1%5D=&garderingar%5B3%5D%5B6%5D%5B2%5D=&garderingar%5B4%5D%5B6%5D%5B0%5D=&garderingar%5B4%5D%5B6%5D%5B1%5D=&garderingar%5B4%5D%5B6%5D%5B2%5D=&garderingar%5B5%5D%5B6%5D%5B0%5D=&garderingar%5B5%5D%5B6%5D%5B1%5D=&garderingar%5B5%5D%5B6%5D%5B2%5D=&garderingar%5B6%5D%5B6%5D%5B0%5D=&garderingar%5B6%5D%5B6%5D%5B1%5D=&garderingar%5B6%5D%5B6%5D%5B2%5D=&garderingar%5B7%5D%5B6%5D%5B0%5D=&garderingar%5B7%5D%5B6%5D%5B1%5D=&garderingar%5B7%5D%5B6%5D%5B2%5D=&garderingar%5B0%5D%5B7%5D%5B0%5D=1&garderingar%5B0%5D%5B7%5D%5B1%5D=X&garderingar%5B0%5D%5B7%5D%5B2%5D=&garderingar%5B1%5D%5B7%5D%5B0%5D=&garderingar%5B1%5D%5B7%5D%5B1%5D=&garderingar%5B1%5D%5B7%5D%5B2%5D=&garderingar%5B2%5D%5B7%5D%5B0%5D=&garderingar%5B2%5D%5B7%5D%5B1%5D=&garderingar%5B2%5D%5B7%5D%5B2%5D=&garderingar%5B3%5D%5B7%5D%5B0%5D=&garderingar%5B3%5D%5B7%5D%5B1%5D=&garderingar%5B3%5D%5B7%5D%5B2%5D=&garderingar%5B4%5D%5B7%5D%5B0%5D=&garderingar%5B4%5D%5B7%5D%5B1%5D=&garderingar%5B4%5D%5B7%5D%5B2%5D=&garderingar%5B5%5D%5B7%5D%5B0%5D=&garderingar%5B5%5D%5B7%5D%5B1%5D=&garderingar%5B5%5D%5B7%5D%5B2%5D=&garderingar%5B6%5D%5B7%5D%5B0%5D=&garderingar%5B6%5D%5B7%5D%5B1%5D=&garderingar%5B6%5D%5B7%5D%5B2%5D=&garderingar%5B7%5D%5B7%5D%5B0%5D=&garderingar%5B7%5D%5B7%5D%5B1%5D=&garderingar%5B7%5D%5B7%5D%5B2%5D=&garderingar%5B0%5D%5B8%5D%5B0%5D=&garderingar%5B0%5D%5B8%5D%5B1%5D=&garderingar%5B0%5D%5B8%5D%5B2%5D=&garderingar%5B1%5D%5B8%5D%5B0%5D=&garderingar%5B1%5D%5B8%5D%5B1%5D=&garderingar%5B1%5D%5B8%5D%5B2%5D=&garderingar%5B2%5D%5B8%5D%5B0%5D=&garderingar%5B2%5D%5B8%5D%5B1%5D=&garderingar%5B2%5D%5B8%5D%5B2%5D=&garderingar%5B3%5D%5B8%5D%5B0%5D=&garderingar%5B3%5D%5B8%5D%5B1%5D=&garderingar%5B3%5D%5B8%5D%5B2%5D=&garderingar%5B4%5D%5B8%5D%5B0%5D=&garderingar%5B4%5D%5B8%5D%5B1%5D=&garderingar%5B4%5D%5B8%5D%5B2%5D=&garderingar%5B5%5D%5B8%5D%5B0%5D=&garderingar%5B5%5D%5B8%5D%5B1%5D=&garderingar%5B5%5D%5B8%5D%5B2%5D=&garderingar%5B6%5D%5B8%5D%5B0%5D=&garderingar%5B6%5D%5B8%5D%5B1%5D=&garderingar%5B6%5D%5B8%5D%5B2%5D=&garderingar%5B7%5D%5B8%5D%5B0%5D=&garderingar%5B7%5D%5B8%5D%5B1%5D=&garderingar%5B7%5D%5B8%5D%5B2%5D=&garderingar%5B0%5D%5B9%5D%5B0%5D=&garderingar%5B0%5D%5B9%5D%5B1%5D=X&garderingar%5B0%5D%5B9%5D%5B2%5D=2&garderingar%5B1%5D%5B9%5D%5B0%5D=&garderingar%5B1%5D%5B9%5D%5B1%5D=&garderingar%5B1%5D%5B9%5D%5B2%5D=&garderingar%5B2%5D%5B9%5D%5B0%5D=&garderingar%5B2%5D%5B9%5D%5B1%5D=&garderingar%5B2%5D%5B9%5D%5B2%5D=&garderingar%5B3%5D%5B9%5D%5B0%5D=&garderingar%5B3%5D%5B9%5D%5B1%5D=&garderingar%5B3%5D%5B9%5D%5B2%5D=&garderingar%5B4%5D%5B9%5D%5B0%5D=&garderingar%5B4%5D%5B9%5D%5B1%5D=&garderingar%5B4%5D%5B9%5D%5B2%5D=&garderingar%5B5%5D%5B9%5D%5B0%5D=&garderingar%5B5%5D%5B9%5D%5B1%5D=&garderingar%5B5%5D%5B9%5D%5B2%5D=&garderingar%5B6%5D%5B9%5D%5B0%5D=&garderingar%5B6%5D%5B9%5D%5B1%5D=&garderingar%5B6%5D%5B9%5D%5B2%5D=&garderingar%5B7%5D%5B9%5D%5B0%5D=&garderingar%5B7%5D%5B9%5D%5B1%5D=&garderingar%5B7%5D%5B9%5D%5B2%5D=&garderingar%5B0%5D%5B10%5D%5B0%5D=&garderingar%5B0%5D%5B10%5D%5B1%5D=&garderingar%5B0%5D%5B10%5D%5B2%5D=&garderingar%5B1%5D%5B10%5D%5B0%5D=&garderingar%5B1%5D%5B10%5D%5B1%5D=&garderingar%5B1%5D%5B10%5D%5B2%5D=&garderingar%5B2%5D%5B10%5D%5B0%5D=&garderingar%5B2%5D%5B10%5D%5B1%5D=&garderingar%5B2%5D%5B10%5D%5B2%5D=&garderingar%5B3%5D%5B10%5D%5B0%5D=&garderingar%5B3%5D%5B10%5D%5B1%5D=&garderingar%5B3%5D%5B10%5D%5B2%5D=&garderingar%5B4%5D%5B10%5D%5B0%5D=&garderingar%5B4%5D%5B10%5D%5B1%5D=&garderingar%5B4%5D%5B10%5D%5B2%5D=&garderingar%5B5%5D%5B10%5D%5B0%5D=&garderingar%5B5%5D%5B10%5D%5B1%5D=&garderingar%5B5%5D%5B10%5D%5B2%5D=&garderingar%5B6%5D%5B10%5D%5B0%5D=&garderingar%5B6%5D%5B10%5D%5B1%5D=&garderingar%5B6%5D%5B10%5D%5B2%5D=&garderingar%5B7%5D%5B10%5D%5B0%5D=&garderingar%5B7%5D%5B10%5D%5B1%5D=&garderingar%5B7%5D%5B10%5D%5B2%5D=&garderingar%5B0%5D%5B11%5D%5B0%5D=&garderingar%5B0%5D%5B11%5D%5B1%5D=&garderingar%5B0%5D%5B11%5D%5B2%5D=&garderingar%5B1%5D%5B11%5D%5B0%5D=&garderingar%5B1%5D%5B11%5D%5B1%5D=&garderingar%5B1%5D%5B11%5D%5B2%5D=&garderingar%5B2%5D%5B11%5D%5B0%5D=&garderingar%5B2%5D%5B11%5D%5B1%5D=&garderingar%5B2%5D%5B11%5D%5B2%5D=&garderingar%5B3%5D%5B11%5D%5B0%5D=&garderingar%5B3%5D%5B11%5D%5B1%5D=&garderingar%5B3%5D%5B11%5D%5B2%5D=&garderingar%5B4%5D%5B11%5D%5B0%5D=&garderingar%5B4%5D%5B11%5D%5B1%5D=&garderingar%5B4%5D%5B11%5D%5B2%5D=&garderingar%5B5%5D%5B11%5D%5B0%5D=&garderingar%5B5%5D%5B11%5D%5B1%5D=&garderingar%5B5%5D%5B11%5D%5B2%5D=&garderingar%5B6%5D%5B11%5D%5B0%5D=&garderingar%5B6%5D%5B11%5D%5B1%5D=&garderingar%5B6%5D%5B11%5D%5B2%5D=&garderingar%5B7%5D%5B11%5D%5B0%5D=&garderingar%5B7%5D%5B11%5D%5B1%5D=&garderingar%5B7%5D%5B11%5D%5B2%5D=&garderingar%5B0%5D%5B12%5D%5B0%5D=&garderingar%5B0%5D%5B12%5D%5B1%5D=X&garderingar%5B0%5D%5B12%5D%5B2%5D=2&garderingar%5B1%5D%5B12%5D%5B0%5D=&garderingar%5B1%5D%5B12%5D%5B1%5D=&garderingar%5B1%5D%5B12%5D%5B2%5D=&garderingar%5B2%5D%5B12%5D%5B0%5D=&garderingar%5B2%5D%5B12%5D%5B1%5D=&garderingar%5B2%5D%5B12%5D%5B2%5D=&garderingar%5B3%5D%5B12%5D%5B0%5D=&garderingar%5B3%5D%5B12%5D%5B1%5D=&garderingar%5B3%5D%5B12%5D%5B2%5D=&garderingar%5B4%5D%5B12%5D%5B0%5D=&garderingar%5B4%5D%5B12%5D%5B1%5D=&garderingar%5B4%5D%5B12%5D%5B2%5D=&garderingar%5B5%5D%5B12%5D%5B0%5D=&garderingar%5B5%5D%5B12%5D%5B1%5D=&garderingar%5B5%5D%5B12%5D%5B2%5D=&garderingar%5B6%5D%5B12%5D%5B0%5D=&garderingar%5B6%5D%5B12%5D%5B1%5D=&garderingar%5B6%5D%5B12%5D%5B2%5D=&garderingar%5B7%5D%5B12%5D%5B0%5D=&garderingar%5B7%5D%5B12%5D%5B1%5D=&garderingar%5B7%5D%5B12%5D%5B2%5D=';

		$_REQUEST['andel_garderingar'] = 'andel_garderingar%5B0%5D=1&andel_garderingar%5B1%5D=0&andel_garderingar%5B2%5D=0&andel_garderingar%5B3%5D=0&andel_garderingar%5B4%5D=0&andel_garderingar%5B5%5D=0&andel_garderingar%5B6%5D=0&andel_garderingar%5B7%5D=0';

		new SystemAjax();
		$this->expectOutputString('1');

		$spel = new Spel();
		$tips = new Tips($spel);

		$system = new System($tips->utdelning, $tips->odds, $tips->streck, $tips->matcher);
		$this->assertEquals($system->garderingar[0][0][0], '');
		$this->assertEquals($system->garderingar[0][7][0], '1');
		$this->assertEquals($system->garderingar[0][7][1], 'X');

		unset($_REQUEST['garderingar'], $_REQUEST['andel_garderingar']);
	}
}
