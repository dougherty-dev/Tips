<?php

/**
 * Klass Prova.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Distribution;

use Tips\Egenskaper\Tick;

/**
 * Klass Prova.
 */
class Prova extends Historik {
	use Tick;

	/**
	 * Pröva tipsrad.
	 */
	public function pröva_tipsrad(string $tipsrad_012): bool {
		$this->beräkna_sannolikhetssummor($tipsrad_012);
		return ∈($this->oddssumma, $this->minsumma, $this->maxsumma) || $this->tick();
	}

	/**
	 * Beräkna sannolikhetssummor.
	 */
	public function beräkna_sannolikhetssummor(string $tipsrad_012): void {
		$this->oddssumma = ($tipsrad_012 === '') ? 0.00 : array_sum(
			array_map(
				fn (array $odds, string $tecken): float => $odds[$tecken],
				$this->odds->sannolikheter,
				str_split($tipsrad_012)
			)
		);
	}

	/**
	 * Annonsera modul.
	 */
	public function annonsera(): string {
		$distval = <<< EOT
<input class="nummer_litet" type="number" min="0" autocomplete="off" id="distribution_minprocent_ext" value="{$this->minprocent}">–<input class="nummer_litet" type="number" min="0" autocomplete="off" id="distribution_maxprocent_ext" value="{$this->maxprocent}">% <button class="distribution_schema" value="[0.1, 2]">0.1–2</button> <button class="distribution_schema" value="[0.1, 3]">0.1–3</button> <button class="distribution_schema" value="[0.1, 5]">0.1–5</button> <button class="distribution_schema" value="[0.1, 10]">0.1–10</button>
EOT;
		return round($this->procentandel, 1) . ' ' .
			$this->attraktionsfaktor($this->attraktionsfaktor, 'distribution_attraktionsfaktor') . $distval;
	}

	/**
	 * Rendera kommentar.
	 */
	public function kommentar(): string {
		return self::class . " {$this->minprocent}–{$this->maxprocent} | a={$this->attraktionsfaktor}";
	}
}
