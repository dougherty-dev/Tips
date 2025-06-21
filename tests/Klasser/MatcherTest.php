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
	 * Ladda matcher och kontrollera egenskaper.
	 */
	public function testMatcher(): void
	{
		new Preludium();
		$this->assertInstanceOf("\Tips\Klasser\Spel", $spel = new Spel());
		$this->assertInstanceOf("\Tips\Klasser\Matcher", $matcher = new Matcher($spel));
		$matcher->hämta_matcher();
	}
}
