<?php

/**
 * Klass Reduktion.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\System;

/**
 * Klass Reduktion.
 */
class Reduktion extends Radera {
	/**
	 * @var int[] $helgarderingar
	 */
	public array $helgarderingar;
	/**
	 * @var int[] $halvgarderingar
	 */
	public array $halvgarderingar;
	/**
	 * @var array<string, int> $reducerad_kod
	 */
	public array $reducerad_kod = [];

	/**
	 * Beräkna garderingar.
	 */
	protected function beräkna_garderingar(): void {
		$this->helgarderingar = $this->halvgarderingar = [];
		foreach ($this->reduktion as $i => $konf) {
			match (true) {
				$konf === ['1', 'X', '2'] => $this->helgarderingar[] = $i,
				$konf === ['1', 'X', ''] || $konf === ['1', '', '2'] || $konf === ['', 'X', '2'] => $this->halvgarderingar[] = $i,
				default => null
			};
		}
	}

	/**
	 * Beräkna reduktion.
	 * @return array<int, string[]> $reduktion
	 */
	protected function beräkna_reduktion(): array {
		$reduktion = [];
		foreach ($this->kod->kod() as $rkod) {
			$reduktion[] = [
				substr($rkod, 0, $this->kod->helgarderingar()),
				substr($rkod, -$this->kod->halvgarderingar())
			];
		}
		return $reduktion;
	}

	/**
	 * Reducera kodord.
	 */
	protected function reducera_kodord(string $kodord): string {
		$str = '';
		foreach ([...$this->helgarderingar, ...$this->halvgarderingar] as $gardering) {
			$str .= $kodord[$gardering];
		}

		return $str;
	}

	/**
	 * Addera kodord.
	 * @param array<int, int[]> $kod_helgarderingar
	 * @param array<int, int[]> $kod_halvgarderingar
	 */
	protected function addera_kodord(
		int $antal_hg,
		array $kod_helgarderingar,
		array $kod_halvgarderingar
	): void {
		/**
		 * $kodord[$j]:
		 * 1X/X2 = 1, 1X2 = 2, annars 0.
		 */
		foreach ($kod_helgarderingar as $nyckel => $kodord) {
			if ($antal_hg) {
				foreach ($kod_halvgarderingar[$nyckel] as $j => $tecken) {
					$kodord[$j] += $tecken;
				}
			}
			$this->reducerad_kod[$this->reducera_kodord(implode('', $kodord))] = 1;
		}
	}
}
