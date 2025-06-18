<?php

/**
 * Klass Spikar.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Autospik;

/**
 * Klass Spikar.
 */
class Spikar extends Preferenser {
	public string $tipsrader = '';
	public int $spikar_rätt = 0;
	/**
	 * @var string[] $tipsvektor
	 */
	public array $tipsvektor = [];

	/**
	 * Beräkna spikar.
	 */
	protected function beräkna_spikar(): void {
		$this->spikar = array_fill(0, abs($this->valda_spikar), -1);
		$this->teckenindex = $this->spikar;
		$this->oddsindex = $this->spikar;

		$this->spikar = array_flip(
			array_slice(
				$this->odds->utfallshistorik->ordnad_historik,
				0,
				$this->valda_spikar,
				true
			)
		);

		foreach ($this->spikar as $i => $spik) {
			$utfall = array_slice($this->odds->utfallshistorik->utfallshistorik[$spik], 0, 3);

			if (empty($utfall)) {
				$this->odds->spel->db->logg->logga("FEL. Autospik: beräkna_spikar(), tom array.");
				return;
			}

			$tecken_012 = (int) array_search(max($utfall), $utfall, true);
			$this->oddsindex[$i] = ($tecken_012 != 1) ? $tecken_012 : 0; // inga kryss, välj i så fall etta
		}
	}
}
