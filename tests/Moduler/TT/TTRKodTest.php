<?php

/**
 * Klass TTRKodTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Moduler\TT;

use PHPUnit\Framework\TestCase;
use Tips\Klasser\Preludium;
use Tips\Moduler\TT\TTRKod;
use Tips\Moduler\TT\TTKod;

/**
 * Klass TTRKodTest.
 */
class TTRKodTest extends TestCase
{
	/**
	 * Testa alla klasser med reduktionskoder samt tillhörande metoder.
	 */
	public function testRKod(): void
	{
		new Preludium();

		foreach (TTRKod::cases() as $rkod) {
			$mapp = 'R' . explode('_', $rkod->name)[4];
			$klass = "\\Tips\\Koder\\$mapp\\" . $rkod->name;
			$this->assertTrue(new $klass() instanceof $klass);

			$this->assertEquals(count($rkod->kod()), $rkod->antal_rader());
			$this->assertCount(4, $rkod->garantitabell());
		}

		$this->assertEquals(TTKod::BCH_8_6_2_3->antal_rader(), 729);
		$this->assertEquals(TTKod::GRUPPKOD_8_3_3->antal_rader(), 567);
	}
}
