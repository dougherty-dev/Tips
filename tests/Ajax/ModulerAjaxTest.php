<?php

/**
 * Klass ModulerAjaxTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Ajax;

use PHPUnit\Framework\TestCase;
use Tips\Klasser\Spel;
use Tips\Ajax\SpelAjax;
use Tips\Ajax\ModulerAjax;
use Tips\Klasser\Utdelning;
use Tips\Klasser\Preludium;
use Tips\Klasser\Tips;
use Tips\Klasser\Moduler;

/**
 * Klass ModulerAjaxTest.
 */
class ModulerAjaxTest extends TestCase
{
	/**
	 * Construct object with argument and verify that the object has the expected properties.
	 */
	public function testModulerAjax(): void
	{
		new Preludium();

		/**
		 * Preparera.
		 */
		$_REQUEST['uppdatera_moduler'] = 'moduler[]=HG&moduler[]=Autospik&moduler[]=FANN&moduler[]=Andel&moduler[]=System&moduler[]=Distribution&moduler[]=Kluster&moduler[]=TT&moduler[]=Vinstgraf';
		new ModulerAjax();
		$spel = new Spel();
		$tips = new Tips($spel);
		$moduler = new Moduler($tips->utdelning, $tips->odds, $tips->streck, $tips->matcher);
		$this->assertCount(9, $moduler->moduler);
		unset($_REQUEST['uppdatera_moduler']);

		$_REQUEST['uppdatera_moduler'] = false;
		new ModulerAjax();
		unset($_REQUEST['uppdatera_moduler']);
	}
}
