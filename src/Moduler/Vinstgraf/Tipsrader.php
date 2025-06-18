<?php

/**
 * Klass Tipsrader.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Vinstgraf;

/**
 * Klass Tipsrader.
 */
class Tipsrader extends Rita {
	public int $antal_rader = 0;
	public int $totalt_antal_rader = 0;
	/** @var array<int, array{string, int}> $tipsrader */ protected array $tipsrader = [];

	/**
	 * Hämta tipsrader.
	 */
	protected function hämta_tipsrader(): void {
		$u13 = $this->utdelning->utdelningsdata();

		foreach ($this->odds->tipsdata() as $omgång => $tipsrad_012) {
			if (isset($u13[$omgång])) {
				$this->totalt_antal_rader++;

				if ($u13[$omgång] === 0) {
					$u13[$omgång] = MAXVINST;
				}

				if (∈($u13[$omgång], $this->utdelning_13_min, $this->utdelning_13_max)) {
					$this->tipsrader[] = [$tipsrad_012, $u13[$omgång]];
				}
			}
		}
	}
}
