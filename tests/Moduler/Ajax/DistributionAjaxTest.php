<?php

/**
 * Klass DistributionAjaxTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Moduler\Ajax;

use PHPUnit\Framework\TestCase;
use Tips\Moduler\Ajax\DistributionAjax;
use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\DBPreferenser;

/**
 * Klass DistributionAjaxTest.
 */
class DistributionAjaxTest extends TestCase
{
	/**
	 * Testa ajaxanrop för att ändra gränser för intervall.
	 */
	public function testDistributionAjaxMinprocent(): void
	{
		new Preludium();

		$_REQUEST['distribution_minprocent'] = 101;
		$_REQUEST['distribution_maxprocent'] = 102;
		$this->assertInstanceOf("\Tips\Moduler\Ajax\DistributionAjax", new DistributionAjax());

		$spel = new Spel();
		$dbprefs = new DBPreferenser($spel->db);
		$dist_minprocent = (float) $dbprefs->hämta_preferens('distribution.distribution_minprocent');
		$dist_maxprocent = (float) $dbprefs->hämta_preferens('distribution.distribution_maxprocent');
		$this->assertNotEquals($dist_minprocent, 101);
		$this->assertNotEquals($dist_maxprocent, 102);
		unset($_REQUEST['distribution_minprocent'], $_REQUEST['distribution_maxprocent']);
	}

	/**
	 * Testa ajaxanrop för att ändra gränser för intervall.
	 */
	public function testDistributionAjaxGrund(): void
	{
		new Preludium();

		$_REQUEST['grund_minprocent'] = 101;
		$_REQUEST['grund_maxprocent'] = 102;
		new DistributionAjax();

		$spel = new Spel();
		$dbprefs = new DBPreferenser($spel->db);
		$grund_minprocent = (float) $dbprefs->hämta_preferens('distribution.grund_minprocent');
		$grund_maxprocent = (float) $dbprefs->hämta_preferens('distribution.grund_maxprocent');
		$this->assertNotEquals($grund_minprocent, 101);
		$this->assertNotEquals($grund_maxprocent, 102);
		unset($_REQUEST['grund_minprocent'], $_REQUEST['grund_maxprocent']);

		$_REQUEST['attraktionsfaktor'] = '1594322';
		new DistributionAjax();
		unset($_REQUEST['attraktionsfaktor']);
	}
}
