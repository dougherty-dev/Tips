<?php

/**
 * Klass Prova.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT\TTGridGenerera;

use Tips\Moduler\TT;
use Tips\Moduler\TT\TTReduceradKod;

/**
 * Klass Prova.
 */
class Prova {
	/** @var string[] $täckningskod */ public array $täckningskod;
	protected TTReduceradKod $tt_reducerad_kod;

	public function __construct(protected TT $tt) {
	}

	/**
	 * Pröva reduktion.
	 */
	protected function pröva_reduktion(string $tipsrad_012): bool {
		return isset($this->tt_reducerad_kod->reducerad_kod[$this->tt_reducerad_kod->reducera_kodord($tipsrad_012)]);
	}

	/**
	 * Pröva täckningsgrad.
	 */
	protected function pröva_täckningskod(string $tipsrad_012): bool {
		return in_array($tipsrad_012, $this->täckningskod, true);
	}

	/**
	 * Pröva spikar.
	 */
	/** @param array<int, array<int, string|null>> $spikar */
	protected function pröva_spikar(array $spikar, int $andel_spikar, string $tipsrad_012): bool {
		$andel_spikade = 0;
		foreach ($spikar as $i => $s) {
			if ($s[(int) $tipsrad_012[$i]] > '') {
				$andel_spikade++;
			}
		}

		return $andel_spikade >= $andel_spikar;
	}

	/**
	 * Pröva teckenintervall.
	 */
	protected function pröva_teckenintervall(string $tipsrad_012): bool {
		$antal_1 = substr_count($tipsrad_012, '0');
		$antal_X = substr_count($tipsrad_012, '1');
		$antal_2 = substr_count($tipsrad_012, '2');
		return in($antal_1, $this->tt->antal_1_min, $this->tt->antal_1_max) &&
			in($antal_X, $this->tt->antal_X_min, $this->tt->antal_X_max) &&
			in($antal_2, $this->tt->antal_2_min, $this->tt->antal_2_max);
	}

	/**
	 * Pröva oddsintervall.
	 */
	protected function pröva_oddsintervall(string $tipsrad_012): bool {
		return in(antal_rätt($tipsrad_012, $this->tt->enkelrad_012), $this->tt->odds_rätt_min, $this->tt->odds_rätt_max);
	}
}
