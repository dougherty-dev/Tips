<?php

/**
 * Klass ProvaRad.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT\TTGridGenerera;

use Tips\Moduler\TT;

/**
 * Klass ProvaRad.
 */
class ProvaRad extends Prova {
	/**
	 * Pröva TT-rad.
	 */
	protected function pröva_rad(string $tipsrad_012): bool {
		$ok = true;

		$this->tt->tt_pröva_reduktion and $ok = $this->pröva_reduktion($tipsrad_012);
		$ok and $this->pröva_intervall($tipsrad_012);

		if ($ok && $this->tt->tt_pröva_spikar) {
			foreach (array_keys($this->tt->spikar) as $index) {
				$ok and $ok = $this->pröva_spikar(
					$this->tt->spikar[$index],
					$this->tt->andel_spikar[$index],
					$tipsrad_012
				);
			}
		}

		return $ok;
	}

	/**
	 * Pröva intervall, delberäkning.
	 */
	private function pröva_intervall(string $tipsrad_012): bool {
		$ok = true;
		$this->tt->tt_pröva_täckning and $ok = $this->pröva_täckningskod($tipsrad_012);
		$ok and $this->tt->tt_pröva_t_intv and $ok = $this->pröva_teckenintervall($tipsrad_012);
		$ok and $this->tt->tt_pröva_o_intv and $ok = $this->pröva_oddsintervall($tipsrad_012);
		return $ok;
	}
}
