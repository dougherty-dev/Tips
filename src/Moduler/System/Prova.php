<?php

/**
 * Klass Prova.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\System;

use Tips\Egenskaper\Tick;

/**
 * Klass Prova.
 */
class Prova extends ReduceradKod {
	use Tick;

	/**
	 * Pröva tipsrad.
	 */
	public function pröva_tipsrad(string $tipsrad_012): bool {
		$ok = true;
		$this->pröva_reduktion and $ok = isset($this->reducerad_kod[$this->reducera_kodord($tipsrad_012)]);

		if ($ok && $this->pröva_garderingar) {
			foreach (array_keys($this->garderingar) as $i) {
				$ok and $this->andel_garderingar[$i] > 0 and $ok = $this->pröva_gardering($this->garderingar[$i], $this->andel_garderingar[$i], $tipsrad_012);
			}
		}

		return $ok || $this->tick();
	}

	/**
	 * Pröva gardering.
	 * @param array<int, string[]> $system
	 */
	private function pröva_gardering(array $system, int $andel_garderingar, string $tipsrad_012): bool {
		$antal_garderade = 0;
		foreach ($system as $i => $s) {
			if ($s[(int) $tipsrad_012[$i]] > '') {
				$antal_garderade++;
			}
		}
		return $antal_garderade >= $andel_garderingar;
	}

	/**
	 * Annonsera modul.
	 */
	public function annonsera(): string {
		return $this->kod->name . ' ' . $this->attraktionsfaktor($this->attraktionsfaktor, 'system_attraktionsfaktor');
	}

	/**
	 * Visa kommentar.
	 */
	public function kommentar(): string {
		return self::class . " {$this->kod->name} | a={$this->attraktionsfaktor}";
	}
}
