<?php

/**
 * Klass HamtaSpikar.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

use Tips\Moduler\TT\Konstanter;

/**
 * Klass HamtaSpikar.
 */
class HamtaSpikar extends HamtaData {
	use Konstanter;

	/**
	 * @var int[] $antal_spikar */ public array $antal_spikar;
	/** @var int[] $andel_spikar */ public array $andel_spikar;
	/** @var array<int, array<int, array<int, string>>> $spikar */ public array $spikar;
	/** @var array<int, string[]> $reduktion */ public array $reduktion;

	/**
	 * Hämta spikar.
	 */
	protected function hämta_spikar(): void {
		/**
		 * Definiera en tom struktur. Hämta preferens.
		 */
		$this->spikar = array_fill(
			0,
			self::TT_MAX_SPIKFÄLT,
			array_fill(0, self::TT_MATCHANTAL, TOM_STRÄNGVEKTOR)
		);
		$spikar = $this->db_preferenser->hämta_preferens("topptips.spikar");

		/**
		 * Spara tom struktur om spikar inte redan sparade.
		 * Packa annars upp spikar i fält och ersätt tom struktur.
		 */
		match ($spikar) {
			'' => $this->db_preferenser->spara_preferens(
				"topptips.spikar",
				implode(',', array_merge([], ...array_merge([], ...$this->spikar)))
			),
			default => $this->spikar = array_chunk(
				array_chunk(explode(',', $spikar), 3),
				self::TT_MATCHANTAL
			)
		};

		$this->antal_spikar = array_fill(0, self::TT_MAX_SPIKFÄLT, 0);
		$this->andel_spikar = $this->antal_spikar;
		$andel_spikar = $this->db_preferenser->hämta_preferens("topptips.andel_spikar");

		if ($andel_spikar !== '') {
			$this->andel_spikar = array_map('intval', explode(',', $andel_spikar));
		}

		foreach ($this->spikar as $index => $spik) {
			foreach ($spik as $tecken) {
				/**
				 * Halvgardering = en ruta tom.
				 */
				$har_hg = array_any($tecken, function (string $value) {
					return $value !== '';
				});

				if ($har_hg) {
					$this->antal_spikar[$index]++;
				}
			}
			if ($this->andel_spikar[$index] > $this->antal_spikar[$index]) {
				$this->antal_spikar[$index] = $this->andel_spikar[$index];
			}
		}

		$this->reduktion = array_fill(0, self::TT_MATCHANTAL, TOM_STRÄNGVEKTOR);
		$reduktion = $this->db_preferenser->hämta_preferens("topptips.reduktion");

		/**
		 * Finns reduktion i preferenser är allt väl.
		 * Annars sparas en tom datastruktur.
		 */
		match ($reduktion !== '') {
			true => $this->reduktion = array_chunk(explode(',', $reduktion), 3),
			false => $this->db_preferenser->spara_preferens(
				"topptips.reduktion",
				implode(',', array_merge([], ...$this->reduktion))
			)
		};
	}
}
