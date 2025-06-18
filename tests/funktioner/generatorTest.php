<?php

/**
 * generatorTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Funktioner;

use PHPUnit\Framework\TestCase;
use Tips\Klasser\Preludium;

/**
 * generatorTest.
 */
class generatorTest extends TestCase
{
	/**
	 * Construct object with argument and verify that the object has the expected properties.
	 */
	public function testCreateObject(): void
	{
		new Preludium();
		$rymd = [TECKENRYMD, TECKENRYMD, TECKENRYMD];
		$tipstecken = '';
		foreach (generera($rymd, 3) as $tecken) {
			$tipstecken .= $tecken;
		}

		$this->assertSame($tipstecken, '000001002010011012020021022100101102110111112120121122200201202210211212220221222');
		/**
		 * = 27 tipsrader med 3 tecken:
		 * 000 = 111
		 * 001
		 * 002
		 * 010
		 * 011
		 * 012
		 * 020
		 * 021
		 * 022
		 * 100
		 * 101
		 * 102
		 * 110
		 * 111 = XXX
		 * 112
		 * 120
		 * 121
		 * 122
		 * 200
		 * 201
		 * 202
		 * 210
		 * 211
		 * 212
		 * 220
		 * 221
		 * 222 = 222
		 */
	}
}
