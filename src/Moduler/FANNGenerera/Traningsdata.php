<?php

/**
 * Klass Traningsdata.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\FANNGenerera;

use Tips\Moduler\FANN;
use Tips\Egenskaper\Varden;

/**
 * Klass Traningsdata.
 */
class Traningsdata {
	use Varden;

	/**
	 * 3 odds + 3 streck in.
	 * 1 tecken eller halvgardering ut.
	 */
	protected int $neuroner_in = 6;
	protected int $neuroner_ut = 1;

	/**
	 * @var array<int, array<int, float[]>> $oddssannolikheter
	 */
	public array $oddssannolikheter;

	/**
	 * @var array<int, array<int, float[]>> $strecksannolikheter
	 */
	public array $strecksannolikheter;

	/**
	 * @var string[] $tipsrader
	 */
	public array $tipsrader;

	/**
	 * Tom konstruktor tills vidare.
	 */
	public function __construct(public FANN $fann) {
	}

	/**
	 * Generera träningsdata.
	 */
	protected function generera_träningsdata(): void {
		$this->oddssannolikheter = [];
		$this->strecksannolikheter = [];
		$this->tipsrader = [];

		/**
		 * Hämta prediktionsdata.
		 */
		$oddsprediktioner = $this->fann->odds->prediktionsdata('odds');
		$streckprediktioner = $this->fann->odds->prediktionsdata('streck');

		foreach ($this->fann->odds->tipsdata($this->u13_min, $this->u13_max) as $omgång => $tipsrad_012) {
			if (isset($oddsprediktioner[$omgång], $streckprediktioner[$omgång])) {
				$this->oddssannolikheter[] = odds_till_sannolikheter($oddsprediktioner[$omgång]);
				$this->strecksannolikheter[] = streck_till_sannolikheter($streckprediktioner[$omgång]);
				$this->tipsrader[] = $tipsrad_012;
			}
		}

		/**
		 * Lämna 10 senaste omgångar för kontroll av resultatet.
		 */
		if ($antal = count($this->oddssannolikheter) > 40) {
			$this->oddssannolikheter = array_slice($this->oddssannolikheter, 0, $andel = $antal - 10);
			$this->strecksannolikheter = array_slice($this->strecksannolikheter, 0, $andel);
			$this->tipsrader = array_slice($this->tipsrader, 0, $andel);
		}

		$this->träningsdata();
	}

	/**
	 * Själva genereringen.
	 */
	private function träningsdata(): void {
		/**
		 * Slumpa tipsraderna.
		 */
		$nycklar = array_keys($this->tipsrader);
		shuffle($nycklar);

		/**
		 * En neuron per match.
		 */
		$antal_neuroner = MATCHANTAL * count($nycklar);
		$träningsdata = "$antal_neuroner {$this->neuroner_in} {$this->neuroner_ut}\n";

		/**
		 * Generera träningsdata.
		 */
		foreach ($nycklar as $omgång) {
			foreach (str_split($this->tipsrader[$omgång]) as $index => $tecken) {
				$odds = $this->oddssannolikheter[$omgång][$index];
				$streck = $this->strecksannolikheter[$omgång][$index];

				/**
				 * Symmetri -1, 0, 1 snarare än 0, 1, 2.
				 */
				$tecken = intval($tecken) - 1;
				$träningsdata .= "{$odds[0]} {$streck[0]} {$odds[1]} {$streck[1]} {$odds[2]} {$streck[2]}\n$tecken\n";
			}
		}

		/**
		 * Spara och logga.
		 */
		$this->fann->logg = date("Y-m-d H:i") . "<br>" .
		match (file_put_contents($this->fann->indatafil, $träningsdata) !== false) {
			true => "Sparade fil.<br>",
			false => "Kunde inte spara fil.<br>"
		};
	}
}
