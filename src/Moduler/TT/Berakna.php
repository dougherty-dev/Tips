<?php

/**
 * Klass Berakna.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

/**
 * Klass Berakna.
 */
class Berakna extends HamtaSpikar {
	/** @var array<int, float[]> $tt_odds */ public array $tt_odds;
	/** @var array<int, float[]> $tt_o_sannolikheter */ public array $tt_o_sannolikheter;
	/** @var array<int, float[]> $tt_streck */ public array $tt_streck;
	/** @var array<int, float[]> $tt_s_sannolikheter */ public array $tt_s_sannolikheter;
	public string $enkelrad_012 = '';
	public string $enkelrad_1X2 = '';

	/**
	 * Beräkna sannolikheter.
	 */
	public function beräkna_sannolikheter(): void {
		$this->tt_o_sannolikheter = odds_till_sannolikheter($this->tt_odds);
		$this->tt_s_sannolikheter = streck_till_sannolikheter($this->tt_streck);
		$this->beräkna_enkelrad();
	}

	/**
	 * Beräkna enkelrad.
	 */
	protected function beräkna_enkelrad(): void {
		foreach ($this->tt_o_sannolikheter as $s) {
			$this->enkelrad_012 .= (string) array_search(ne_max($s), $s, true);
		}
		$this->enkelrad_1X2 = siffror_till_symboler($this->enkelrad_012);
	}
}
