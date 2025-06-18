<?php

/**
 * Klass OmgangTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Klasser;

use PHPUnit\Framework\TestCase;
use Tips\Klasser\Omgang;
use Tips\Klasser\Utdelning;
use Tips\Klasser\Spel;
use Tips\Klasser\Preludium;
use Tips\Klasser\Tips;
use Tips\Klasser\Generera;
use Tips\Klasser\Omgang\Uppmarkning;

/**
 * Klass OmgangTest.
 */
class OmgangTest extends TestCase
{
	/**
	 * Construct object with argument and verify that the object has the expected properties.
	 */
	public function testCreateObject(): void
	{
		new Preludium();
		$omg = new Omgang();
		$this->expectOutputRegex('*Resultat*');
		$this->assertInstanceOf("\Tips\Klasser\Omgang", $omg);
		$this->assertInstanceOf("\Tips\Klasser\Spel", $spel = new Spel());
		$this->assertInstanceOf("\Tips\Klasser\Tips", $tips = new Tips($spel));
		$this->assertObjectHasProperty('filnamn', $spel);
		$this->assertTrue($spel->omgång_existerar($spel->omgång));
		$this->assertInstanceOf("\Tips\Klasser\Omgang\Uppmarkning", new Uppmarkning($tips));
		$this->assertInstanceOf("\Tips\Klasser\Generera", new Generera($tips));
	}
}
