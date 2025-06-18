<?php

/**
 * Klass HG.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\HG;

/**
 * Klass HG.
 */
class Utdata extends Preferenser {
	/**
	 * Beräkna utdata.
	 * @param array<int, float[]> $sannolikheter
	 * @return string[]
	 */
	public function beräkna_utdata(array $sannolikheter): array {
		$utdata = [];
		/**
		 * X2, 1X eller 12 beroende på kriterier.
		 */
		foreach ($sannolikheter as $sannolikhet) {
			$utdata[] = match (ne_min($sannolikhet)) {
				$sannolikhet[0] => '12',
				$sannolikhet[2] => '01',
				default => '02'
			};
		}
		return $utdata;
	}

	/**
	 * Ta fram mest sannolika halvgarderingar enligt kriterier.
	 * @return int[] $hg_vektor
	 */
	protected function hg_vektor(): array {
		$hg_vektor = [];

		$prediktioner = $this->odds->prediktionsdata('odds');
		$tipsdata = $this->odds->tipsdata();

		foreach ($tipsdata as $omgång => $tipsrad_012) {
			if (isset($prediktioner[$omgång])) {
				$sannolikheter = odds_till_sannolikheter($prediktioner[$omgång]);
				$utdata = $this->beräkna_utdata($sannolikheter);

				$hg_rätt = antal_rätt($utdata, $tipsrad_012);
				$hg_vektor[$hg_rätt] = isset($hg_vektor[$hg_rätt]) ? $hg_vektor[$hg_rätt] + 1 : 1;
			}
		}

		krsort($hg_vektor);
		return $hg_vektor;
	}
}
