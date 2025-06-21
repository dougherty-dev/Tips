<?php

/**
 * tipsraderTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Funktioner;

use PHPUnit\Framework\TestCase;
use Tips\Klasser\Preludium;

/**
 * tipsraderTest.
 */
class tipsraderTest extends TestCase
{
	/**
	 * Tester för tipsrader med metoder.
	 */
	public function testTipsrader(): void
	{
		new Preludium();

		$tipsrader = [
			'11X1111112211',
			'11X1111112212',
			'11X1111112221',
			'11X1111112222',
			'11XX111112212',
			'11XX111112221',
			'1121111111222',
			'1121111112121',
			'1121111112211',
			'1121111211111',
			'1121111211211'
		];

		$tipsrader_012 = array_map('symboler_till_siffror', $tipsrader);
		$this->assertEquals(array_map('siffror_till_symboler', $tipsrader_012), $tipsrader);

		$tipsrader_bas36 = bas3till36($tipsrader_012);
		$this->assertEquals(bas36till3($tipsrader_bas36), $tipsrader_012);

		$this->assertEquals(kommatisera('0011000002202'), '1,1,X,X,1,1,1,1,1,2,2,1,2');

		$rätt_rad = '1X21X11112X11';
		$rättvektor = [9, 8, 8, 7, 7, 7, 7, 9, 10, 8, 8];

		foreach ($tipsrader as $i => $rad) {
			$this->assertEquals(antal_rätt($rad, $rätt_rad), $rättvektor[$i]);
		}
	}
}
