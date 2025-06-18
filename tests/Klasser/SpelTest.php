<?php

/**
 * Klass SpelTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Klasser;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Tips\Klasser\Spel\Sekvenser;
use Tips\Klasser\Spel\Komplett;
use Tips\Klasser\Spel\Spara;
use Tips\Klasser\Spel\Omgang;
use Tips\Klasser\Spel;
use Tips\Klasser\Preludium;

/**
 * Klass SpelTest.
 */
class SpelTest extends TestCase
{
	/**
	 * Construct object with argument and verify that the object has the expected properties.
	 */
	public function testCreateObject(): void
	{
		new Preludium();
		$this->assertInstanceOf("\Tips\Klasser\Spel\Sekvenser", new Sekvenser());
		$this->assertInstanceOf("\Tips\Klasser\Spel\Komplett", new Komplett());
		$this->assertInstanceOf("\Tips\Klasser\Spel\Spara", new Spara());
		$this->assertInstanceOf("\Tips\Klasser\Spel\Omgang", new Omgang());
		$spel = new Spel();
		$this->assertObjectHasProperty('filnamn', $spel);
	}
}
