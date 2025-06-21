<?php

/**
 * Klass SystemTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Moduler\System;

use PHPUnit\Framework\TestCase;
use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\Tips;
use Tips\Klasser\Speltyp;
use Tips\Moduler\System;

/**
 * Klass SystemTest.
 */
class SystemTest extends TestCase
{
	/**
	 * Tester för System med metoder.
	 */
	public function testCreateObject(): void
	{
		new Preludium();
		$spel = new Spel();
		$tips = new Tips($spel);
		$system = new System($tips->utdelning, $tips->odds, $tips->streck, $tips->matcher);
		$this->assertInstanceOf("\Tips\Moduler\System", $system);

		$system->visa_modul();
		$this->expectOutputRegex('*Kodanalys*');
		$system->pröva_tipsrad('0112011201121');

		$system->radera_sekvens(4905, Speltyp::Stryktipset, 1);
		$system->radera_omgång(4905, Speltyp::Stryktipset);
	}
}
