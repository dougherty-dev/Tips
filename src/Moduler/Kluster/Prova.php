<?php

/**
 * Klass Prova.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Kluster;

/**
 * Klass Prova.
 */
class Prova extends Preferenser {
	/**
	 * Pröva tipsrad.
	 */
	public function pröva_tipsrad(string $tipsrad_012): bool {
		[$x, $y] = $this->graf->tipsgrafskoordinater($tipsrad_012);
		foreach ($this->rektanglar as [$x1, $y1, $x2, $y2]) {
			if (in($x, $x1, $x2) && in($y, $y1, $y2)) {
				return true;
			}
		}

		return $this->tick();
	}

	/**
	 * Annonsera modul.
	 */
	public function annonsera(): string {
		return "r={$this->min_radie}, n={$this->min_antal} " .
			$this->attraktionsfaktor($this->attraktionsfaktor, 'kluster_attraktionsfaktor');
	}

	/**
	 * Visa kommentar.
	 */
	public function kommentar(): string {
		return self::class . " r={$this->min_radie}, n={$this->min_antal} | a={$this->attraktionsfaktor}";
	}
}
