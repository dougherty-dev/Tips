<?php

/**
 * Klass Ratt.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\FANNGenerera;

/**
 * Klass Ratt.
 */
class Ratt extends Parametrar {
	/**
	 * Beräkna fördelning av antal rätt för FANN.
	 * @return int[]
	 */
	protected function beräkna_fannrätt(): array {
		$fannrätt = [];

		foreach ($this->oddssannolikheter as $i => $oddssannolikheter) {
			$rådata = [];
			foreach ($oddssannolikheter as $j => $odds) {
				$streck = $this->strecksannolikheter[$i][$j];
				$res = fann_run($this->fann->fann, [$odds[0], $streck[0], $odds[1], $streck[1], $odds[2], $streck[2]]);
				$rådata[] = $res[0];
			}

			$utdata = $this->fann->beräkna_utdata($rådata, $this->fann->limiter);
			$antal_rätt = antal_rätt($utdata, $this->tipsrader[$i]);
			$fannrätt[$antal_rätt] = isset($fannrätt[$antal_rätt]) ? $fannrätt[$antal_rätt] + 1 : 1;
		}

		krsort($fannrätt);
		return $fannrätt;
	}
}
