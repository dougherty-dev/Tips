<?php

/**
 * Klass TTAjaxTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Moduler\Ajax;

use PHPUnit\Framework\TestCase;
use Tips\Moduler\Ajax\TTAjax;
use Tips\Moduler\TT;
use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\Tips;
use Tips\Klasser\DBPreferenser;

/**
 * Klass TTAjaxTest.
 */
class TTAjaxTest extends TestCase
{
	/**
	 * Testa ajaxanrop.
	 */
	public function testTTAjax(): void
	{
		new Preludium();

		$_REQUEST['tt_strategi'] = 'TT-strategi';
		$this->expectOutputString('1');
		$this->assertInstanceOf("\Tips\Moduler\Ajax\TTAjax", new TTAjax());

		$spel = new Spel();
		$tips = new Tips($spel);

		$topptips = new TT($tips->utdelning, $tips->odds, $tips->streck, $tips->matcher);

		$this->assertEquals($topptips->strategi, 'TT-strategi');
		unset($_REQUEST['tt_strategi']);


		$_REQUEST['tt_reduktion'] = 'tt_reduktion%5B0%5D%5B0%5D=1&tt_reduktion%5B0%5D%5B1%5D=X&tt_reduktion%5B0%5D%5B2%5D=2&tt_reduktion%5B1%5D%5B0%5D=1&tt_reduktion%5B1%5D%5B1%5D=X&tt_reduktion%5B1%5D%5B2%5D=2&tt_reduktion%5B2%5D%5B0%5D=1&tt_reduktion%5B2%5D%5B1%5D=X&tt_reduktion%5B2%5D%5B2%5D=2&tt_reduktion%5B3%5D%5B0%5D=1&tt_reduktion%5B3%5D%5B1%5D=X&tt_reduktion%5B3%5D%5B2%5D=2&tt_reduktion%5B4%5D%5B0%5D=1&tt_reduktion%5B4%5D%5B1%5D=X&tt_reduktion%5B4%5D%5B2%5D=2&tt_reduktion%5B5%5D%5B0%5D=&tt_reduktion%5B5%5D%5B1%5D=&tt_reduktion%5B5%5D%5B2%5D=&tt_reduktion%5B6%5D%5B0%5D=&tt_reduktion%5B6%5D%5B1%5D=&tt_reduktion%5B6%5D%5B2%5D=&tt_reduktion%5B7%5D%5B0%5D=1&tt_reduktion%5B7%5D%5B1%5D=X&tt_reduktion%5B7%5D%5B2%5D=2';
		new TTAjax();

		$reduktion = '1,X,2,1,X,2,1,X,2,1,X,2,1,X,2,,,,,,,1,X,2';
		$dbprefs = new DBPreferenser($spel->db);
		$red = $dbprefs->hämta_preferens('topptips.reduktion');
		$this->assertEquals($reduktion, $red);
		unset($_REQUEST['tt_reduktion']);

		/**
		 * Limiter
		 */
		$_REQUEST['tt_gränser'] = true;
		$_REQUEST['tt_odds_rätt_min'] = '17'; // max 8
		$_REQUEST['tt_odds_rätt_max'] = '5';
		$_REQUEST['tt_antal_1_min'] = '7';
		$_REQUEST['tt_antal_1_max'] = '5';
		$_REQUEST['tt_antal_X_min'] = '7';
		$_REQUEST['tt_antal_X_max'] = '5';
		$_REQUEST['tt_antal_2_min'] = '7';
		$_REQUEST['tt_antal_2_max'] = '5';
		$ttajax = new TTAjax();

		$dbprefs = new DBPreferenser($spel->db);
		$min = $dbprefs->hämta_preferens('topptips.odds_rätt_min');
		$this->assertEquals($min, $ttajax::TT_ODDS_RÄTT_MIN); // 2

		unset(
			$_REQUEST['tt_gränser'],
			$_REQUEST['tt_odds_rätt_min'],
			$_REQUEST['tt_odds_rätt_max'],
			$_REQUEST['tt_antal_1_min'],
			$_REQUEST['tt_antal_1_max'],
			$_REQUEST['tt_antal_X_min'],
			$_REQUEST['tt_antal_X_max'],
			$_REQUEST['tt_antal_2_min'],
			$_REQUEST['tt_antal_2_max']
		);

		/**
		 * Matchdata
		 */
		$_REQUEST['tt_matchdata'] = 'tt_odds%5B0%5D%5B%5D=3.71&tt_odds%5B0%5D%5B%5D=3.30&tt_odds%5B0%5D%5B%5D=2.20&tt_odds%5B1%5D%5B%5D=2.20&tt_odds%5B1%5D%5B%5D=3.40&tt_odds%5B1%5D%5B%5D=3.60&tt_odds%5B2%5D%5B%5D=1.28&tt_odds%5B2%5D%5B%5D=6.25&tt_odds%5B2%5D%5B%5D=12.00&tt_odds%5B3%5D%5B%5D=1.74&tt_odds%5B3%5D%5B%5D=3.50&tt_odds%5B3%5D%5B%5D=4.50&tt_odds%5B4%5D%5B%5D=2.55&tt_odds%5B4%5D%5B%5D=3.00&tt_odds%5B4%5D%5B%5D=2.75&tt_odds%5B5%5D%5B%5D=2.70&tt_odds%5B5%5D%5B%5D=3.10&tt_odds%5B5%5D%5B%5D=2.55&tt_odds%5B6%5D%5B%5D=3.35&tt_odds%5B6%5D%5B%5D=3.50&tt_odds%5B6%5D%5B%5D=2.02&tt_odds%5B7%5D%5B%5D=1.19&tt_odds%5B7%5D%5B%5D=6.00&tt_odds%5B7%5D%5B%5D=13.00';
		new TTAjax();

		$spel = new Spel();
		$tips = new Tips($spel);

		$topptips = new TT($tips->utdelning, $tips->odds, $tips->streck, $tips->matcher);
		$this->assertEquals($topptips->tt_odds[0][0], 3.71);
		unset($_REQUEST['tt_matchdata']);
	}

	/**
	 * Fler parametrar.
	 */
	public function testTTAjax2(): void {
		new Preludium();
		/**
		 * Koder
		 */
		$_REQUEST['tt_kod'] = 'GRUPPKOD_8_3_3';
		new TTAjax();
		$this->expectOutputRegex('*GRUPPKOD_8_3_3*');
		unset($_REQUEST['tt_kod']);

		$_REQUEST['tt_rkod'] = 'R_6_1_132_1';
		new TTAjax();
		unset($_REQUEST['tt_rkod']);

		$_REQUEST['tt_kod'] = 'Obefintlig';
		new TTAjax();
		unset($_REQUEST['tt_kod']);

		$_REQUEST['tt_rkod'] = 'Obefintlig';
		new TTAjax();
		unset($_REQUEST['tt_rkod']);

		/**
		 * Bokföring
		 */
		$_REQUEST['tt_visa_antal_bokföringar'] = true;
		new TTAjax();
		unset($_REQUEST['tt_visa_antal_bokföringar']);

		$_REQUEST['tt_radera_bokföring'] = true;
		$_REQUEST['id'] = 0;
		new TTAjax();
		unset($_REQUEST['tt_radera_bokföring'], $_REQUEST['id']);

		$_REQUEST['tt_uppdatera_bokföring'] = true;
		$_REQUEST['id'] = 0;
		$_REQUEST['värde'] = 5;
		$_REQUEST['kolumn'] = 'vinst';
		new TTAjax();
		unset($_REQUEST['tt_uppdatera_bokföring'], $_REQUEST['id'], $_REQUEST['värde'], $_REQUEST['kolumn']);

		/**
		 * Rader
		 */
		$_REQUEST['tt_antal_rader'] = 2;
		new TTAjax();
		unset($_REQUEST['tt_antal_rader']);

		$_REQUEST['topptipstyp'] = 'Topptipset Europa';
		new TTAjax();
		unset($_REQUEST['topptipstyp']);

		$_REQUEST['tt_status'] = 'teststatus';
		$_REQUEST['tt_id'] = 'inget';
		new TTAjax();
		unset($_REQUEST['tt_status'], $_REQUEST['tt_id']);
	}

	/**
	 * Fler parametrar.
	 */
	public function testTTAjax3(): void {
		new Preludium();
		/**
		 * Koder
		 */

		$_REQUEST['tt_spikar'] = 'tt_spikar%5B0%5D%5B0%5D%5B0%5D=&tt_spikar%5B0%5D%5B0%5D%5B1%5D=&tt_spikar%5B0%5D%5B0%5D%5B2%5D=&tt_spikar%5B1%5D%5B0%5D%5B0%5D=&tt_spikar%5B1%5D%5B0%5D%5B1%5D=&tt_spikar%5B1%5D%5B0%5D%5B2%5D=&tt_spikar%5B2%5D%5B0%5D%5B0%5D=&tt_spikar%5B2%5D%5B0%5D%5B1%5D=&tt_spikar%5B2%5D%5B0%5D%5B2%5D=&tt_spikar%5B3%5D%5B0%5D%5B0%5D=&tt_spikar%5B3%5D%5B0%5D%5B1%5D=&tt_spikar%5B3%5D%5B0%5D%5B2%5D=&tt_spikar%5B4%5D%5B0%5D%5B0%5D=&tt_spikar%5B4%5D%5B0%5D%5B1%5D=&tt_spikar%5B4%5D%5B0%5D%5B2%5D=&tt_spikar%5B5%5D%5B0%5D%5B0%5D=&tt_spikar%5B5%5D%5B0%5D%5B1%5D=&tt_spikar%5B5%5D%5B0%5D%5B2%5D=&tt_spikar%5B0%5D%5B1%5D%5B0%5D=&tt_spikar%5B0%5D%5B1%5D%5B1%5D=&tt_spikar%5B0%5D%5B1%5D%5B2%5D=&tt_spikar%5B1%5D%5B1%5D%5B0%5D=&tt_spikar%5B1%5D%5B1%5D%5B1%5D=&tt_spikar%5B1%5D%5B1%5D%5B2%5D=&tt_spikar%5B2%5D%5B1%5D%5B0%5D=&tt_spikar%5B2%5D%5B1%5D%5B1%5D=&tt_spikar%5B2%5D%5B1%5D%5B2%5D=&tt_spikar%5B3%5D%5B1%5D%5B0%5D=&tt_spikar%5B3%5D%5B1%5D%5B1%5D=&tt_spikar%5B3%5D%5B1%5D%5B2%5D=&tt_spikar%5B4%5D%5B1%5D%5B0%5D=&tt_spikar%5B4%5D%5B1%5D%5B1%5D=&tt_spikar%5B4%5D%5B1%5D%5B2%5D=&tt_spikar%5B5%5D%5B1%5D%5B0%5D=&tt_spikar%5B5%5D%5B1%5D%5B1%5D=&tt_spikar%5B5%5D%5B1%5D%5B2%5D=&tt_spikar%5B0%5D%5B2%5D%5B0%5D=1&tt_spikar%5B0%5D%5B2%5D%5B1%5D=&tt_spikar%5B0%5D%5B2%5D%5B2%5D=&tt_spikar%5B1%5D%5B2%5D%5B0%5D=&tt_spikar%5B1%5D%5B2%5D%5B1%5D=&tt_spikar%5B1%5D%5B2%5D%5B2%5D=&tt_spikar%5B2%5D%5B2%5D%5B0%5D=&tt_spikar%5B2%5D%5B2%5D%5B1%5D=&tt_spikar%5B2%5D%5B2%5D%5B2%5D=&tt_spikar%5B3%5D%5B2%5D%5B0%5D=&tt_spikar%5B3%5D%5B2%5D%5B1%5D=&tt_spikar%5B3%5D%5B2%5D%5B2%5D=&tt_spikar%5B4%5D%5B2%5D%5B0%5D=&tt_spikar%5B4%5D%5B2%5D%5B1%5D=&tt_spikar%5B4%5D%5B2%5D%5B2%5D=&tt_spikar%5B5%5D%5B2%5D%5B0%5D=&tt_spikar%5B5%5D%5B2%5D%5B1%5D=&tt_spikar%5B5%5D%5B2%5D%5B2%5D=&tt_spikar%5B0%5D%5B3%5D%5B0%5D=1&tt_spikar%5B0%5D%5B3%5D%5B1%5D=&tt_spikar%5B0%5D%5B3%5D%5B2%5D=&tt_spikar%5B1%5D%5B3%5D%5B0%5D=&tt_spikar%5B1%5D%5B3%5D%5B1%5D=&tt_spikar%5B1%5D%5B3%5D%5B2%5D=&tt_spikar%5B2%5D%5B3%5D%5B0%5D=&tt_spikar%5B2%5D%5B3%5D%5B1%5D=&tt_spikar%5B2%5D%5B3%5D%5B2%5D=&tt_spikar%5B3%5D%5B3%5D%5B0%5D=&tt_spikar%5B3%5D%5B3%5D%5B1%5D=&tt_spikar%5B3%5D%5B3%5D%5B2%5D=&tt_spikar%5B4%5D%5B3%5D%5B0%5D=&tt_spikar%5B4%5D%5B3%5D%5B1%5D=&tt_spikar%5B4%5D%5B3%5D%5B2%5D=&tt_spikar%5B5%5D%5B3%5D%5B0%5D=&tt_spikar%5B5%5D%5B3%5D%5B1%5D=&tt_spikar%5B5%5D%5B3%5D%5B2%5D=&tt_spikar%5B0%5D%5B4%5D%5B0%5D=&tt_spikar%5B0%5D%5B4%5D%5B1%5D=&tt_spikar%5B0%5D%5B4%5D%5B2%5D=&tt_spikar%5B1%5D%5B4%5D%5B0%5D=&tt_spikar%5B1%5D%5B4%5D%5B1%5D=&tt_spikar%5B1%5D%5B4%5D%5B2%5D=&tt_spikar%5B2%5D%5B4%5D%5B0%5D=&tt_spikar%5B2%5D%5B4%5D%5B1%5D=&tt_spikar%5B2%5D%5B4%5D%5B2%5D=&tt_spikar%5B3%5D%5B4%5D%5B0%5D=&tt_spikar%5B3%5D%5B4%5D%5B1%5D=&tt_spikar%5B3%5D%5B4%5D%5B2%5D=&tt_spikar%5B4%5D%5B4%5D%5B0%5D=&tt_spikar%5B4%5D%5B4%5D%5B1%5D=&tt_spikar%5B4%5D%5B4%5D%5B2%5D=&tt_spikar%5B5%5D%5B4%5D%5B0%5D=&tt_spikar%5B5%5D%5B4%5D%5B1%5D=&tt_spikar%5B5%5D%5B4%5D%5B2%5D=&tt_spikar%5B0%5D%5B5%5D%5B0%5D=&tt_spikar%5B0%5D%5B5%5D%5B1%5D=&tt_spikar%5B0%5D%5B5%5D%5B2%5D=&tt_spikar%5B1%5D%5B5%5D%5B0%5D=&tt_spikar%5B1%5D%5B5%5D%5B1%5D=&tt_spikar%5B1%5D%5B5%5D%5B2%5D=&tt_spikar%5B2%5D%5B5%5D%5B0%5D=&tt_spikar%5B2%5D%5B5%5D%5B1%5D=&tt_spikar%5B2%5D%5B5%5D%5B2%5D=&tt_spikar%5B3%5D%5B5%5D%5B0%5D=&tt_spikar%5B3%5D%5B5%5D%5B1%5D=&tt_spikar%5B3%5D%5B5%5D%5B2%5D=&tt_spikar%5B4%5D%5B5%5D%5B0%5D=&tt_spikar%5B4%5D%5B5%5D%5B1%5D=&tt_spikar%5B4%5D%5B5%5D%5B2%5D=&tt_spikar%5B5%5D%5B5%5D%5B0%5D=&tt_spikar%5B5%5D%5B5%5D%5B1%5D=&tt_spikar%5B5%5D%5B5%5D%5B2%5D=&tt_spikar%5B0%5D%5B6%5D%5B0%5D=&tt_spikar%5B0%5D%5B6%5D%5B1%5D=&tt_spikar%5B0%5D%5B6%5D%5B2%5D=&tt_spikar%5B1%5D%5B6%5D%5B0%5D=&tt_spikar%5B1%5D%5B6%5D%5B1%5D=&tt_spikar%5B1%5D%5B6%5D%5B2%5D=&tt_spikar%5B2%5D%5B6%5D%5B0%5D=&tt_spikar%5B2%5D%5B6%5D%5B1%5D=&tt_spikar%5B2%5D%5B6%5D%5B2%5D=&tt_spikar%5B3%5D%5B6%5D%5B0%5D=&tt_spikar%5B3%5D%5B6%5D%5B1%5D=&tt_spikar%5B3%5D%5B6%5D%5B2%5D=&tt_spikar%5B4%5D%5B6%5D%5B0%5D=&tt_spikar%5B4%5D%5B6%5D%5B1%5D=&tt_spikar%5B4%5D%5B6%5D%5B2%5D=&tt_spikar%5B5%5D%5B6%5D%5B0%5D=&tt_spikar%5B5%5D%5B6%5D%5B1%5D=&tt_spikar%5B5%5D%5B6%5D%5B2%5D=&tt_spikar%5B0%5D%5B7%5D%5B0%5D=1&tt_spikar%5B0%5D%5B7%5D%5B1%5D=&tt_spikar%5B0%5D%5B7%5D%5B2%5D=&tt_spikar%5B1%5D%5B7%5D%5B0%5D=&tt_spikar%5B1%5D%5B7%5D%5B1%5D=&tt_spikar%5B1%5D%5B7%5D%5B2%5D=&tt_spikar%5B2%5D%5B7%5D%5B0%5D=&tt_spikar%5B2%5D%5B7%5D%5B1%5D=&tt_spikar%5B2%5D%5B7%5D%5B2%5D=&tt_spikar%5B3%5D%5B7%5D%5B0%5D=&tt_spikar%5B3%5D%5B7%5D%5B1%5D=&tt_spikar%5B3%5D%5B7%5D%5B2%5D=&tt_spikar%5B4%5D%5B7%5D%5B0%5D=&tt_spikar%5B4%5D%5B7%5D%5B1%5D=&tt_spikar%5B4%5D%5B7%5D%5B2%5D=&tt_spikar%5B5%5D%5B7%5D%5B0%5D=&tt_spikar%5B5%5D%5B7%5D%5B1%5D=&tt_spikar%5B5%5D%5B7%5D%5B2%5D=';

		$_REQUEST['tt_andel_spikar'] = 'tt_andel_spikar%5B0%5D=2&tt_andel_spikar%5B1%5D=0&tt_andel_spikar%5B2%5D=0&tt_andel_spikar%5B3%5D=0&tt_andel_spikar%5B4%5D=0&tt_andel_spikar%5B5%5D=0';

		new TTAjax();
		$this->expectOutputString('1');

		$spel = new Spel();
		$tips = new Tips($spel);

		$topptips = new TT($tips->utdelning, $tips->odds, $tips->streck, $tips->matcher);
		$this->assertEquals($topptips->spikar[0][0][0], '');
		$this->assertEquals($topptips->spikar[0][2][0], '1');
		$this->assertEquals($topptips->spikar[0][3][0], '1');

		unset($_REQUEST['tt_spikar'], $_REQUEST['tt_andel_spikar']);
	}
}
