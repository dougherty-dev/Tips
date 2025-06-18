<?php

/**
 * Klass Preferenser.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Andel;

use Tips\Egenskaper\Tick;

/**
 * Klass Andel.
 */
class Prova extends Preferenser {
	use Tick;

	/**
	 * Pröva tipsrad.
	 */
	public function pröva_tipsrad(string $tipsrad_012): bool {
		return $this->pröva_andelar($this->teckenfördelning($tipsrad_012)) || $this->tick();
	}

	/**
	 * Pröva andelar.
	 * @param int[] $vektor
	 */
	protected function pröva_andelar(array $vektor): bool {
		return ∈($vektor[0], $this->andel_1_min, $this->andel_1_max) &&
			∈($vektor[1], $this->andel_x_min, $this->andel_x_max) &&
			∈($vektor[2], $this->andel_2_min, $this->andel_2_max);
	}

	/**
	 * Teckenfördelning.
	 * @return int[]
	 */
	protected function teckenfördelning(string $tipsrad_012): array {
		return [substr_count($tipsrad_012, '0'), substr_count($tipsrad_012, '1'), substr_count($tipsrad_012, '2')];
	}

	/**
	 * Annonsera modul.
	 */
	public function annonsera(): string {
		return "1: {$this->andel_1_min}–{$this->andel_1_max}, X: {$this->andel_x_min}–{$this->andel_x_max}, 2: {$this->andel_2_min}–{$this->andel_2_max} " .
			$this->attraktionsfaktor($this->attraktionsfaktor, 'andel_attraktionsfaktor');
	}
}
