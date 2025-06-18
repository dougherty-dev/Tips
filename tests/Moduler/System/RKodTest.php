<?php

/**
 * Klass RKodTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Moduler\System;

use PHPUnit\Framework\TestCase;
use Tips\Klasser\Preludium;
use Tips\Moduler\System\RKod;

/**
 * Klass RKodTest.
 */
class RKodTest extends TestCase
{
	/**
	 * Testa alla klasser med reduktionskoder samt tillhörande metoder.
	 */
	public function testRKod(): void
	{
		new Preludium();
		foreach (RKod::cases() as $rkod) {
			$mapp = 'R' . explode('_', $rkod->name)[4];
			$klass = "\\Tips\\Koder\\$mapp\\" . $rkod->name;
			$this->assertTrue(new $klass() instanceof $klass);

			$this->assertEquals(count($rkod->kod()), $rkod->antal_rader());
			$this->assertCount(4, $rkod->garantitabell());
		}
	}
}
