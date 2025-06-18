<?php

/**
 * assistenterTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Funktioner;

use PHPUnit\Framework\TestCase;
use Tips\Klasser\Preludium;

/**
 * assistenterTest.
 */
class assistenterTest extends TestCase
{
	/**
	 * Construct object with argument and verify that the object has the expected properties.
	 */
	public function testCreateObject(): void
	{
		new Preludium();
		$this->assertTrue(∈(2, 1, 3));
		$this->assertFalse(∈(1, 2, 3));

		$this->assertEquals(ne_max([2, 3]), 3);
		$this->assertEquals(ne_max([]), 0);

		$this->assertEquals(ne_min([2, 3]), 2);
		$this->assertEquals(ne_min([]), 0);

		$this->assertEquals(vektorprodukt([2, 3], [4, 5]), 23);
		$this->assertNotEquals(vektorprodukt([3, 3], [4, 5]), 23);

		$this->assertEquals(flyttal("45,7125"), 45.7125);
		$this->assertNotEquals(flyttal("45,7125"), 45.71);

		$this->assertEquals(procenttal(0.56), 56.00);
		$this->assertNotEquals(procenttal(0.64), 64.01);

		[$datum, $spelstopp, $dag] = spelstopp("2025-06-14T15:59:00+02:002025-06-14");
		$this->assertEquals($datum, "2025-06-14");
		$this->assertEquals($spelstopp, "15:59:00");
		$this->assertEquals($dag, "Lördag");

		unset($_REQUEST);
		$_REQUEST['i'] = '3';
		$_REQUEST['j'] = '2';
		$_REQUEST['k'] = '4';
		$_REQUEST['l'] = '1';
		$this->assertEquals(extrahera(), [3, 2, 4, 1]);

		unset($_REQUEST);
		$_REQUEST['i'] = 3;
		$_REQUEST['j'] = 2;
		$_REQUEST['k'] = 4;
		$_REQUEST['l'] = 1;

		$this->assertEquals(extrahera(), [0, 0, 0, 0]);

		$matris = [
			[[0, 1, 2], [3, 4, 5], [6, 7, 8]]
		];

		$platt_matris = [0, 1, 2, 3, 4, 5, 6, 7, 8];

		$this->assertEquals(platta_matris($matris), $platt_matris);
		$this->assertEquals(återbygg_matris(implode(', ', $platt_matris)), $matris);

		unset($_REQUEST['i'], $_REQUEST['j'], $_REQUEST['k'], $_REQUEST['l']);
		$this->assertEquals(extrahera(), []);

		// $json = hämta_objekt('https://jsonplaceholder.typicode.com/todos/1');
		// $this->assertIsObject($json);
		$this->assertNull(hämta_objekt(''));
	}
}
