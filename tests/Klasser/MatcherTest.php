<?php

/**
 * Klass MatcherTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Klasser;

use PHPUnit\Framework\TestCase;
use Tips\Klasser\Spel;
use Tips\Klasser\Preludium;
use Tips\Klasser\Matcher;

/**
 * Klass MatcherTest.
 * @covers \Tips\Tests\Klasser
 */
class MatcherTest extends TestCase
{
	/**
	 * Construct object with argument and verify that the object has the expected properties.
	 */
	public function testCreateObject(): void
	{
		new Preludium();
//		$this->assertInstanceOf("\Tips\Klasser\Omgang", new Omgang());
		$this->assertInstanceOf("\Tips\Klasser\Spel", $spel = new Spel());
		$this->assertInstanceOf("\Tips\Klasser\Matcher", $matcher = new Matcher($spel));
		$matcher->hämta_matcher();

		$this->assertObjectHasProperty('filnamn', $spel);
		$this->assertTrue($spel->omgång_existerar($spel->omgång));
	}
}
