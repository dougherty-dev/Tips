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
			$ok = $this->pröva_garderingar($tipsrad_012);
		}

		return $ok || $this->tick();
	}

	/**
	 * Pröva garderingar.
	 * Reducera komplexitet.
	 */
	private function pröva_garderingar(string $tipsrad_012): bool {
		$ok = true;

		/**
		 * Iterera över garderingar.
		 */
		foreach (array_keys($this->garderingar) as $index) {
			$ok and $this->andel_garderingar[$index] > 0 and $ok = $this->pröva_gardering(
				$this->garderingar[$index],
				$this->andel_garderingar[$index],
				$tipsrad_012
			);
		}

		return $ok;
	}

	/**
	 * Pröva gardering.
	 * @param array<int, string[]> $garderingar
	 */
	private function pröva_gardering(array $garderingar, int $andel_garderingar, string $tipsrad_012): bool {
		$antal_garderade = 0;
		foreach ($garderingar as $index => $gardering) {
			if ($gardering[(int) $tipsrad_012[$index]] > '') {
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
