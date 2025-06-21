<?php

/**
 * Klass OmgangTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Klasser;

use PHPUnit\Framework\TestCase;
use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\Tips;
use Tips\Klasser\Omgang;
use Tips\Klasser\Generera;
use Tips\Klasser\Omgang\Uppmarkning;

/**
 * Klass OmgangTest.
 */
class OmgangTest extends TestCase
{
	/**
	 * Ladda omgång, kontrollera flöde, generering, filer med mera.
	 */
	public function testOmgang(): void
	{
		$prel = new Preludium();
		$this->assertInstanceOf("\Tips\Klasser\Preludium", $prel);

		if (is_file(GRAF . "/ack_investeringsgraf.png")) {
			unlink(GRAF . "/ack_investeringsgraf.png");
		}

		if (is_file(GRAF . "/investeringsgraf.png")) {
			unlink(GRAF . "/investeringsgraf.png");
		}

		if (is_file(GRAF . "/kombinationsgraf.png")) {
			unlink(GRAF . "/kombinationsgraf.png");
		}

		if (is_file(GRAF . "/TT-vinstgraf.png")) {
			unlink(GRAF . "/TT-vinstgraf.png");
		}

		if (is_file(BAS . GENERERADE . "/2025/stryktipset/stryktipset-t1-o4905-s1.txt")) {
			unlink(BAS . GENERERADE . "/2025/stryktipset/stryktipset-t1-o4905-s1.txt");
		}

		$this->assertInstanceOf("\Tips\Klasser\Omgang", new Omgang());
		$this->expectOutputRegex('*Resultat*');

		$this->assertInstanceOf("\Tips\Klasser\Spel", $spel = new Spel());
		$this->assertInstanceOf("\Tips\Klasser\Tips", $tips = new Tips($spel));

		$this->assertInstanceOf("\Tips\Klasser\Omgang\Uppmarkning", new Uppmarkning($tips));
		$this->assertInstanceOf("\Tips\Klasser\Generera", new Generera($tips));
	}
}
