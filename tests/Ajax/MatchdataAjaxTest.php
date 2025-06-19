<?php

/**
 * Klass MatchdataAjaxTest.
 * Författare: Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Ajax;

use PHPUnit\Framework\TestCase;
use Tips\Klasser\Spel;
use Tips\Ajax\MatchdataAjax;
use Tips\Klasser\Preludium;
use Tips\Klasser\Tips;

/**
 * Klass MatchdataAjaxTest.
 */
class MatchdataAjaxTest extends TestCase
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

		$serialized = 's:3020:"spelstopp=&omg%C3%A5ng=4314&%C3%A5r=2013&vecka=49&tipsrad=XXX12X11X1X2X&utdelning%5B%5D=5669551&utdelning%5B%5D=22867&utdelning%5B%5D=1168&utdelning%5B%5D=269&vinnare%5B%5D=2&vinnare%5B%5D=120&vinnare%5B%5D=1879&vinnare%5B%5D=16960&matchstatus%5B%5D=1&lag%5B%5D=Southampton%20-%20Manchester%20C&resultat%5B%5D=1-1&odds%5B0%5D%5B%5D=4.60&odds%5B0%5D%5B%5D=3.80&odds%5B0%5D%5B%5D=1.67&streck%5B0%5D%5B%5D=18&streck%5B0%5D%5B%5D=22&streck%5B0%5D%5B%5D=60&matchstatus%5B%5D=1&lag%5B%5D=Crystal%20P%20-%20Cardiff&resultat%5B%5D=2-0&odds%5B1%5D%5B%5D=2.55&odds%5B1%5D%5B%5D=2.95&odds%5B1%5D%5B%5D=2.90&streck%5B1%5D%5B%5D=40&streck%5B1%5D%5B%5D=32&streck%5B1%5D%5B%5D=28&matchstatus%5B%5D=1&lag%5B%5D=Liverpool%20-%20West%20Ham&resultat%5B%5D=4-1&odds%5B2%5D%5B%5D=1.30&odds%5B2%5D%5B%5D=5.00&odds%5B2%5D%5B%5D=8.75&streck%5B2%5D%5B%5D=84&streck%5B2%5D%5B%5D=10&streck%5B2%5D%5B%5D=6&matchstatus%5B%5D=1&lag%5B%5D=Stoke%20-%20Chelsea&resultat%5B%5D=3-2&odds%5B3%5D%5B%5D=5.80&odds%5B3%5D%5B%5D=3.70&odds%5B3%5D%5B%5D=1.57&streck%5B3%5D%5B%5D=11&streck%5B3%5D%5B%5D=21&streck%5B3%5D%5B%5D=68&matchstatus%5B%5D=1&lag%5B%5D=West%20Bromwich%20-%20Norwich&resultat%5B%5D=0-2&odds%5B4%5D%5B%5D=1.70&odds%5B4%5D%5B%5D=3.55&odds%5B4%5D%5B%5D=4.75&streck%5B4%5D%5B%5D=64&streck%5B4%5D%5B%5D=22&streck%5B4%5D%5B%5D=14&matchstatus%5B%5D=1&lag%5B%5D=Birmingham%20-%20Middlesbrough&resultat%5B%5D=2-2&odds%5B5%5D%5B%5D=2.30&odds%5B5%5D%5B%5D=3.30&odds%5B5%5D%5B%5D=2.80&streck%5B5%5D%5B%5D=50&streck%5B5%5D%5B%5D=28&streck%5B5%5D%5B%5D=22&matchstatus%5B%5D=1&lag%5B%5D=Brighton%20-%20Leicester&resultat%5B%5D=3-1&odds%5B6%5D%5B%5D=2.70&odds%5B6%5D%5B%5D=3.20&odds%5B6%5D%5B%5D=2.50&streck%5B6%5D%5B%5D=29&streck%5B6%5D%5B%5D=29&streck%5B6%5D%5B%5D=42&matchstatus%5B%5D=1&lag%5B%5D=Ipswich%20-%20Huddersfield&resultat%5B%5D=2-1&odds%5B7%5D%5B%5D=1.85&odds%5B7%5D%5B%5D=3.35&odds%5B7%5D%5B%5D=4.00&streck%5B7%5D%5B%5D=55&streck%5B7%5D%5B%5D=25&streck%5B7%5D%5B%5D=20&matchstatus%5B%5D=1&lag%5B%5D=Leeds%20-%20Watford&resultat%5B%5D=3-3&odds%5B8%5D%5B%5D=2.25&odds%5B8%5D%5B%5D=3.40&odds%5B8%5D%5B%5D=2.90&streck%5B8%5D%5B%5D=56&streck%5B8%5D%5B%5D=23&streck%5B8%5D%5B%5D=21&matchstatus%5B%5D=1&lag%5B%5D=Millwall%20-%20Wigan&resultat%5B%5D=2-1&odds%5B9%5D%5B%5D=2.70&odds%5B9%5D%5B%5D=3.15&odds%5B9%5D%5B%5D=2.50&streck%5B9%5D%5B%5D=38&streck%5B9%5D%5B%5D=28&streck%5B9%5D%5B%5D=34&matchstatus%5B%5D=1&lag%5B%5D=Queens%20PR%20-%20Blackburn&resultat%5B%5D=0-0&odds%5B10%5D%5B%5D=1.60&odds%5B10%5D%5B%5D=3.65&odds%5B10%5D%5B%5D=5.20&streck%5B10%5D%5B%5D=71&streck%5B10%5D%5B%5D=18&streck%5B10%5D%5B%5D=11&matchstatus%5B%5D=1&lag%5B%5D=Sheffield%20W%20-%20Nottingham&resultat%5B%5D=0-1&odds%5B11%5D%5B%5D=3.10&odds%5B11%5D%5B%5D=3.35&odds%5B11%5D%5B%5D=2.10&streck%5B11%5D%5B%5D=28&streck%5B11%5D%5B%5D=29&streck%5B11%5D%5B%5D=43&matchstatus%5B%5D=1&lag%5B%5D=Yeovil%20-%20Charlton&resultat%5B%5D=2-2&odds%5B12%5D%5B%5D=2.55&odds%5B12%5D%5B%5D=3.20&odds%5B12%5D%5B%5D=2.60&streck%5B12%5D%5B%5D=39&streck%5B12%5D%5B%5D=28&streck%5B12%5D%5B%5D=33";';

		$_REQUEST['spara_matchdata'] = unserialize($serialized);

		new MatchdataAjax();
		$spel = new Spel();
		$tips = new Tips($spel);
		$this->assertEquals('XXX12X11X1X2X', $tips->utdelning->tipsrad);
		$this->assertEquals(5669551, $tips->utdelning->utdelning[0]);
		unset($_REQUEST['spara_matchdata']);
	}
}
