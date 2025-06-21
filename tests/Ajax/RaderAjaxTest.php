<?php

/**
 * Klass RaderAjaxTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Ajax;

use PHPUnit\Framework\TestCase;
use Tips\Klasser\Spel;
use Tips\Ajax\RaderAjax;
use Tips\Klasser\Preludium;
use Tips\Klasser\Tips;

/**
 * Klass RaderAjaxTest.
 */
class RaderAjaxTest extends TestCase
{
	/**
	 * Tester för RaderAjax med metoder.
	 */
	public function testRaderAjax(): void
	{
		new Preludium();

		/**
		 * Preparera.
		 */

		$_REQUEST['fil'] = 'LzIwMjUvc3RyeWt0aXBzZXQvc3RyeWt0aXBzZXQtdDEtbzQ5MDUtczEudHh0';

		$this->assertInstanceOf("\Tips\Ajax\RaderAjax", new RaderAjax());
		$this->expectOutputRegex("*Stryktipset\r\nE,1,1,1*");
		unset($_REQUEST['fil']);
	}
}
