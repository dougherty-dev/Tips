<?php

/**
 * Klass Sannolikheter.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Prediktioner;

/**
 * Klass Sannolikheter.
 */
class Sannolikheter extends Spara {
	/** @var array<int, float[]> $sannolikheter */ public array $sannolikheter;
	/** @var float[] $maxsannolikheter */ public array $maxsannolikheter = [];
	/** @var float[] $minsannolikheter */ public array $minsannolikheter = [];
	/** @var float[] $maxprediktioner */ public array $maxprediktioner = [];
	/** @var float[] $minprediktioner */ public array $minprediktioner = [];

	/**
	 * Omvandla prediktioner till sannolikheter.
	 */
	protected function prediktioner_till_sannolikheter(): void {
		$this->sannolikheter = match (true) {
			$this->tabell === 'streck' => streck_till_sannolikheter($this->prediktioner),
			matris_saknar_nollelement($this->prediktioner) => odds_till_sannolikheter($this->prediktioner),
			default => TOM_ODDSMATRIS,
		};

		foreach ($this->sannolikheter as $s) {
			$this->maxsannolikheter[] = (float) ne_max($s);
			$this->minsannolikheter[] = (float) ne_min($s);
		}

		foreach ($this->prediktioner as $p) {
			$this->maxprediktioner[] = (float) ne_max($p);
			$this->minprediktioner[] = (float) ne_min($p);
		}

		$this->komplett = matris_saknar_nollelement($this->sannolikheter);
	}
}
