<?php

/**
 * Klass HistorisktUtfall.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\JusteradePrediktioner;

use Tips\Klasser\DBPreferenser;
use Tips\Klasser\Prediktioner;

/**
 * Klass HistorisktUtfall.
 * Beräkna prediktioner retroaktivt för att i bayesisk mening
 * uppskatta verkliga odds.
 */
class HistorisktUtfall {
	protected DBPreferenser $db_preferenser;
	/**
	 * @var array<int, float[]> $odds_j
	 */
	protected array $odds_j;

	/**
	 * @var array<int, float[]> $streck_j
	 */
	protected array $streck_j;

	/**
	 * Initiera.
	 */
	public function __construct(public Prediktioner $prediktioner) {
		$this->db_preferenser = new DBPreferenser($this->prediktioner->spel->db);
	}

	/**
	 * Beräkna historiskt utfall.
	 */
	protected function historiskt_utfall(): void {
		/**
		 * Tre kolumner för procentsatser mellan 0 och 100.
		 */
		$this->streck_j = array_fill(0, 3, array_fill(0, 101, 0));
		$this->odds_j = $this->streck_j;
		$cstreck = $this->odds_j;
		$codds = $cstreck;

		/**
		 * Prediktioner för alla omgångar.
		 */
		$oddsprediktioner = $this->prediktioner->prediktionsdata('odds');
		$streckprediktioner = $this->prediktioner->prediktionsdata('streck');

		/**
		 * Summera sannolikheter och utfall.
		 */
		foreach ($this->prediktioner->tipsdata() as $omgång => $tipsrad_012) {
			if (isset($oddsprediktioner[$omgång], $streckprediktioner[$omgång])) {
				$oddssannolikheter = odds_till_sannolikheter($oddsprediktioner[$omgång]);
				$strecksannolikheter = streck_till_sannolikheter($streckprediktioner[$omgång]);

				foreach ($oddssannolikheter as $index => $oddssannolikhet) {
					$strecksannolikhet = $strecksannolikheter[$index];
					$os_procent = array_map('procenttal', $oddssannolikhet);
					$ss_procent = array_map('procenttal', $strecksannolikhet);

					foreach (array_keys($oddssannolikhet) as $j) {
						$codds[$j][$os_procent[$j]]++;
						$cstreck[$j][$ss_procent[$j]]++;
					}

					$tecken_012 = intval($tipsrad_012[$index]);
					$this->odds_j[$tecken_012][$os_procent[$tecken_012]]++;
					$this->streck_j[$tecken_012][$ss_procent[$tecken_012]]++;
				}
			}
		}

		$this->procentuell_fördelning($codds, $cstreck);
	}

	/**
	 * Procentuell fördelning.
	 * @param array<int, float[]> $codds
	 * @param array<int, float[]> $cstreck
	 */
	private function procentuell_fördelning(array $codds, array $cstreck): void {
		/**
		 * Omvandla summering till procentuell fördelning.
		 */
		foreach ($this->odds_j as $index => $oddssannolikhet) {
			foreach (array_keys($oddssannolikhet) as $j) {
				$this->odds_j[$index][$j] /= max($codds[$index][$j], 1);
				$this->streck_j[$index][$j] /= max($cstreck[$index][$j], 1);
			}
		}
	}
}
