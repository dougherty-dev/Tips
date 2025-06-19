<?php

/**
 * Klass PreferenserAjaxTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Ajax;

use PHPUnit\Framework\TestCase;
use Tips\Klasser\Spel;
use Tips\Ajax\PreferenserAjax;
use Tips\Klasser\Preferenser;
use Tips\Klasser\Preludium;
use Tips\Klasser\Tips;

/**
 * Klass PreferenserAjaxTest.
 */
class PreferenserAjaxTest extends TestCase
{
	public function testModulerAjax(): void
	{
		new Preludium();

		$_REQUEST['api'] = 'mitt-api';
		new PreferenserAjax();
		$this->expectOutputRegex('*Parallella*');
		$spel = new Spel();
		$prefs = new Preferenser($spel->db);
		$this->assertEquals('mitt-api', $prefs->api);
		unset($_REQUEST['api']);

		$_REQUEST['trådar'] = '27';
		new PreferenserAjax();
		$spel = new Spel();
		$prefs = new Preferenser($spel->db);
		$this->assertEquals(27, $prefs->trådar);
		unset($_REQUEST['trådar']);

		$_REQUEST['trådar'] = '26';
		new PreferenserAjax();
		$spel = new Spel();
		$prefs = new Preferenser($spel->db);
		$this->assertNotEquals(26, $prefs->trådar);
		unset($_REQUEST['trådar']);

		$_REQUEST['trådar'] = '9';
		new PreferenserAjax();
		unset($_REQUEST['trådar']);

		$_REQUEST['php'] = $prefs->php;
		$_REQUEST['fcgi'] = 'php219 turbo';
		new PreferenserAjax();
		$spel = new Spel();
		$prefs = new Preferenser($spel->db);
		$this->assertEquals('php219 turbo', $prefs->fcgi);
		unset($_REQUEST['php']);
		unset($_REQUEST['fcgi']);

		$_REQUEST['antal_rader'] = '90000';
		new PreferenserAjax();
		$spel = new Spel();
		$prefs = new Preferenser($spel->db);
		$this->assertEquals(10, $prefs->max_rader);
		unset($_REQUEST['antal_rader']);

		$_REQUEST['antal_rader'] = '513';
		new PreferenserAjax();
		$spel = new Spel();
		$prefs = new Preferenser($spel->db);
		$this->assertEquals(513, $prefs->max_rader);
		unset($_REQUEST['antal_rader']);

		$_REQUEST['antal_rader'] = '9';
		new PreferenserAjax();
		$spel = new Spel();
		$prefs = new Preferenser($spel->db);
		$this->assertEquals(10, $prefs->max_rader);
		unset($_REQUEST['antal_rader']);

		$_REQUEST['u13_min'] = '6000';
		$_REQUEST['u13_max'] = '100000';
		new PreferenserAjax();
		$spel = new Spel();
		$prefs = new Preferenser($spel->db);
		$this->assertEquals(6000, $prefs->u13_min);
		$this->assertEquals(100000, $prefs->u13_max);
		unset($_REQUEST['u13_min']);
		unset($_REQUEST['u13_max']);
	}
}
