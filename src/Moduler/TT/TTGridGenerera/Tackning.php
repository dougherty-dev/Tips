<?php

/**
 * Klass Tackning.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT\TTGridGenerera;

use Tips\Moduler\TT;
use Tips\Moduler\TT\TTReduceradKod;

/**
 * Klass Tackning.
 */
class Tackning extends ProvaRad {
	/** @var string[] $täckningskod */ public array $täckningskod;

	/**
	 * Hämta relevant kod.
	 */
	protected function täckningskod(): void {
		if ($this->tt->tt_pröva_täckning) {
			$class = "\\Tips\\Koder\\" . $this->tt->kod->name;
			$system = new $class();
			$this->täckningskod = $system->kod;
		}
	}

	/**
	 * Generator för alla tipsrader.
	 */
	protected function generera(): void {
		$this->tt->beräkna_sannolikheter();
		$this->tt_reducerad_kod = new TTReduceradKod($this->tt);
		$rymd = array_fill(0, $this->tt::TT_MATCHANTAL, TECKENRYMD);
		foreach (generera($rymd, $this->tt::TT_MATCHANTAL) as $tipsrad_012) {
			$this->pröva_rad($tipsrad_012) and $this->tt->rader[] = $tipsrad_012;
		}

		shuffle($this->tt->rader);
	}

	/**
	 * Rita TT-graf.
	 */
	protected function rita(string $tipsrad_012, int $färg): void {
		[$x, $y] = $this->tt->graf->tipsgrafskoordinater($tipsrad_012, array_slice(KUBER, MATCHANTAL - $this->tt::TT_MATCHANTAL));
		$this->tt->graf->sätt_pixel($x, $y, $färg);
	}
}
