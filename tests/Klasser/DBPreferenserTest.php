<?php

/**
 * Klass DBPreferenserTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Klasser;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Tips\Klasser\DBPreferenser;
use Tips\Klasser\Spel;
use Tips\Klasser\Preludium;

/**
 * Klass DBPreferenserTest.
 */
class DBPreferenserTest extends TestCase
{
	/**
	 * Tester för DBPreferenser med metoder.
	 */
	public function testDBPreferenser(): void
	{
		new Preludium();
		$spel = new Spel();
		$dbpref = new DBPreferenser($spel->db);
		$this->assertInstanceOf("\Tips\Klasser\DBPreferenser", $dbpref);
		$this->assertFalse($dbpref->preferens_finns('preferenser.ickeexisterande'));
		$this->assertTrue($dbpref->preferens_finns('preferenser.api'));
	}
}
