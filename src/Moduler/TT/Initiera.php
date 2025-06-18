<?php

/**
 * Klass Initiera.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

use Tips\Klasser\Utdelning;
use Tips\Klasser\Prediktioner;
use Tips\Klasser\Matcher;
use Tips\Klasser\Graf;
use Tips\Klasser\DBPreferenser;
use Tips\Moduler\TT\TTKod;
use Tips\Moduler\TT\TTRKod;
use Tips\Moduler\TT\Konstanter;

/**
 * Klass Initiera.
 */
class Initiera {
	use Konstanter;

	public Graf $graf;
	public DBPreferenser $db_preferenser;

	/** @var array<int, float[]> $tt_tom_oddsmatris */ public array $tt_tom_oddsmatris;
	/** @var float[] $tt_odds_ordnade */ public array $tt_odds_ordnade;
	/** @var array<int, float[]> $tt_odds */ public array $tt_odds;
	/** @var array<int, float[]> $tt_streck */ public array $tt_streck;
	/** @var string[] $hemmalag */ public array $hemmalag = [];
	/** @var string[] $bortalag */ public array $bortalag = [];
	/** @var string[] $typer */ public array $typer = ['typ' => '', 'produkt' => '', 'externa' => ''];
	public string $tipsgraf = '';

	public function __construct(
		public Utdelning $utdelning,
		public Prediktioner $odds,
		public Prediktioner $streck,
		public Matcher $matcher
	) {
	}

	/**
	 * Initiera topptips.
	 */
	protected function initiera(): void {
		$this->tt_tom_oddsmatris = array_fill(0, self::TT_MATCHANTAL, TOM_ODDSVEKTOR);

		$this->graf = new Graf(81, 81);
		if (file_exists(GRAF . self::TT_BILDFIL)) {
			$this->tipsgraf = $this->graf->rendera_tipsgraf(self::TT_BILDFIL);
		}

		$this->tt_streck = $this->tt_odds = array_fill(0, self::TT_MATCHANTAL, TOM_ODDSVEKTOR);
		$this->hemmalag = $this->bortalag = array_fill(0, self::TT_MATCHANTAL, '');

		/**
		 * Ordinarie eller kopplat till stryk- eller europatips.
		 */
		$this->typer['typ'] = $this->db_preferenser->hämta_preferens('topptips.typ');

		if ($this->typer['typ'] === '') {
			$this->typer['typ'] = self::TOPPTIPSTYPER['namn'][0];
		}

		$index = array_search($this->typer['typ'], self::TOPPTIPSTYPER['namn']);
		$this->typer['produkt'] = self::TOPPTIPSTYPER['api'][$index];
		$this->typer['externa'] = self::TOPPTIPSTYPER['externa'][$index];
	}
}
