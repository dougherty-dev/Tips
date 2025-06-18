<?php

/**
 * Klass GridResultatModuler.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Omgang;

use Tips\Klasser\Tips;

/**
 * Klass GridResultatModuler.
 */
class GridResultatModuler {
	protected Tips $tips;
	/** @var int[] $rättvektor */ protected array $rättvektor = [];
	protected int $antal_rader = 0;
	protected int $vinst = 0;
	protected bool $spelad = false;

	public function __construct(Tips $tips) {
		$this->tips = $tips;
		$this->rättvektor = array_fill(0, MATCHANTAL + 1, 0);
		$this->antal_rader = count($this->tips->spelade->tipsvektor);
		$this->spelad = ($this->tips->utdelning->har_tipsrad && $this->antal_rader > 0);
	}

	/**
	 * Visa resultat för enskilda moduler.
	 */
	protected function moduler(): string {
		$modulsträng = '';
		foreach ($this->tips->moduler->moduldata as $modul => [$ok, $annons]) {
			$modulsträng .= t(7, "$ok $modul $annons<br>");
		}
		return $modulsträng;
	}
}
