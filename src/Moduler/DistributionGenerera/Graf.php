<?php

/**
 * Klass Graf.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\DistributionGenerera;

/**
 * Klass Graf.
 * Rita graf över distribution.
 * 25 % av alla utfall finns inom 1 % distribution.
 * 50 % inom 5 %.
 * 65 % inom 10 %.
 * Jättevinsterna faller utanför detta intervall.
 */
class Graf extends Rendera {
	/**
	 * @var int[] $distribution
	 */
	protected array $distribution = [];

	/**
	 * Uppdatera graf.
	 */
	protected function uppdatera_graf(): void {
		if (count($this->distribution) === 0) {
			return;
		}

		/**
		 * Oddssummor.
		 */
		[$oddssumma_min, $oddssumma_max, $oddssumma_utfall] = $this->oddssummor();
		$distmax_antal_rader = max($this->distribution);

		/**
		 * Deltan i korordinater.
		 */
		[$dx, $dy] = [
			$this->dist->graf->bredd / ($oddssumma_max - $oddssumma_min),
			$this->dist->graf->höjd / $distmax_antal_rader
		];

		/**
		 * Nollställ parametrar.
		 */
		$delsumma = 0.0;
		[$this->dist->maxsumma, $this->dist->minsumma, $this->dist->andelssumma] = [0, 0, 0];

		/**
		 * Iterera över koordinater.
		 */
		foreach ($this->distribution as $xkoord => $ykoord) {
			/**
			 * Koordinater.
			 */
			[$x, $y] = [
				intval(($xkoord - $oddssumma_min) * $dx),
				intval($this->dist->graf->höjd - $ykoord * $dy)
			];

			/**
			 * Plussa andelssumma.
			 */
			if ($xkoord > $oddssumma_utfall) {
				$this->dist->andelssumma += $ykoord;
			}

			/**
			 * Gränslinjer.
			 */
			$delsumma += $ykoord;
			$andel = 100 * $delsumma / MATCHRYMD;

			/**
			 * Rendera linjer.
			 */
			$this->rendera_linjer($andel, $x, $y, $xkoord, $oddssumma_utfall);
		}

		/**
		 * Uppdatera procentandel för distribution, relativt totala matchrymden 3^13.
		 */
		$this->dist->procentandel = round(100 * $this->dist->andelssumma / MATCHRYMD, 3);

		/**
		 * Rendera text.
		 */
		$this->rendera_text($distmax_antal_rader, $oddssumma_min, $oddssumma_max, $oddssumma_utfall);

		/**
		 * Spara graf.
		 */
		$this->spara_graf();
	}

	/**
	 * Rendera gränslinjer i dist.
	 */
	private function rendera_linjer(float $andel, int $x, int $y, string $xkoord, float $oddssumma_utfall): void {
		/**
		 * Rita procentsatser med linjer i gröna nyanser.
		 * Intensitet ökar med avstånd från origo.
		 * Använder 0.5 %, 1 %, 2%, 3 %, 5 % och 10 %.
		 * En procentenhet motsvarar 15943 rader.
		 */
		$this->percentage_lines($andel, $x, $xkoord);

		/**
		 * Rita aktuellt utfall med röd linje.
		 * Motsvarar den momentana sannolikhetssumman för odds för omgången.
		 */
		if ($oddssumma_utfall == $xkoord) {
			$this->dist->graf->sätt_linje($x, 0, $x, $this->dist->graf->höjd, $this->dist->graf->röd);
		}

		/**
		 * Rita maxprocent med vit linje.
		 */
		if ($this->dist->maxsumma == 0 && $andel > $this->dist->minprocent) {
			$this->dist->maxsumma = (float) $xkoord;
			$this->dist->graf->sätt_linje($x, 0, $x, $this->dist->graf->höjd - 50, $this->dist->graf->vit);
		}

		/**
		 * Rita minprocent med vit linje.
		 * Reducera höjd på linje för att ge plats åt eventuell undre linje.
		 */
		if ($this->dist->minsumma == 0 && $andel > $this->dist->maxprocent) {
			$this->dist->minsumma = (float) $xkoord;
			$this->dist->graf->sätt_linje($x, 0, $x, $this->dist->graf->höjd - 50, $this->dist->graf->vit);
		}

		/**
		 * Rita kurva med blå färg.
		 */
		$this->dist->graf->sätt_pixel($x, $y, $this->dist->graf->blå);
	}

	/**
	 * Rita ut textinformation i graf.
	 */
	private function rendera_text(
		int $distmax_antal_rader,
		float $oddssumma_min,
		float $oddssumma_max,
		float $oddssumma_utfall
	): void {
		/**
		 * Rendera omgångsdata med gul text.
		 */
		$this->dist->graf->sätt_text(20, 20, "{$this->tips->odds->spel->speltyp->produktnamn()} {$this->tips->odds->spel->omgång}: utdelning {$this->tips->utdelning->utdelning[0]}", $this->dist->graf->gul);

		/**
		 * Rendera distributionsdata med gul text.
		 */
		$distmax = array_search($distmax_antal_rader, $this->distribution, true);
		$this->dist->graf->sätt_text(20, 40, "x_min: $oddssumma_min, mitt: $distmax ($distmax_antal_rader), x_max: $oddssumma_max", $this->dist->graf->gul);

		/**
		 * Rendera aktuellt utfall med röd text.
		 */
		if ($oddssumma_utfall > 0) {
			$this->dist->graf->sätt_text(20, 60, "utfall: $oddssumma_utfall ({$this->dist->andelssumma}) | andel: {$this->dist->procentandel} %", $this->dist->graf->röd);
		}
	}

	/**
	 * Spara distributionsgraf.
	 */
	private function spara_graf(): void {
		/**
		 * Spara och beräkna sannolikhetssummor.
		 * Logga händelse.
		 */
		$this->dist->graf->spara_tipsgraf($this->dist->bildfil);
		$this->dist->beräkna_sannolikhetssummor($this->tips->utdelning->tipsrad_012);
		$this->spara_distribution();
		$this->tips->odds->spel->db->logg->logga(self::class . ' ✅ Uppdaterade graf, sparade distribution.');
	}
}
