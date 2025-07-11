<?php

/**
 * Klass PGenereraTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Parallellisering;

use PHPUnit\Framework\TestCase;
use Tips\Klasser\Preludium;
use Tips\Parallellisering\PGenerera;

/**
 * Klass PGenereraTest.
 */
class PGenereraTest extends TestCase
{
	/**
	 * Testa PGenerera med metoder.
	 */
	public function testPGenerera(): void
	{
		new Preludium();

		$_REQUEST['i'] = '1';
		$_REQUEST['j'] = '0';
		$_REQUEST['k'] = '0';
		$_REQUEST['l'] = '0';
		$this->assertInstanceOf("\Tips\Parallellisering\PGenerera", new PGenerera());
		unset($_REQUEST['i'], $_REQUEST['j'], $_REQUEST['k'], $_REQUEST['l']);
	}
}
