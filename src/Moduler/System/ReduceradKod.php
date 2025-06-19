<?php

/**
 * Klass ReduceradKod.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\System;

/**
 * Klass ReduceradKod.
 * Reducera kod enligt reduktionskod.
 * R-system definieras i Koder.
 */
class ReduceradKod extends Reduktion {
	/**
	 * Beräkna reducerad kod.
	 */
	protected function beräkna_reducerad_kod(): void {
		$this->beräkna_garderingar();
		$antal_hg = count($this->halvgarderingar);

		/**
		 * Kräv överensstämmelse i garderingar med system.
		 */
		if (
			count($this->helgarderingar) != $this->kod->helgarderingar() ||
			$antal_hg != $this->kod->halvgarderingar()
		) {
			return;
		}

		$kod_halvgarderingar = [];
		$kod_helgarderingar = [];

		/**
		 * Reducera.
		 */
		foreach ($this->beräkna_reduktion() as $projektion) {
			foreach ($projektion as $index => $kodord) {
				$kod = array_map('intval', str_split($kodord));
				$temp = NOLLRAD;

				/**
				 * Helgardering.
				 * $projektion håller helgarderingar i [0], halvgarderingar i [1]
				 */
				if ($index === 0) {
					foreach ($kod as $nyckel => $tecken) {
						$temp[$this->helgarderingar[$nyckel]] = $tecken;
					}

					$kod_helgarderingar[] = $temp;
					continue;
				}

				$this->halvgarderingar($antal_hg, $kod, $temp, $kod_halvgarderingar);
			}
		}

		$this->addera_kodord($antal_hg, $kod_helgarderingar, $kod_halvgarderingar);
	}

	/**
	 * Halvgarderingar.
	 * @param int[] $kod
	 * @param int[] $temp
	 * @param array<int, int[]> $kod_halvgarderingar
	 */
	private function halvgarderingar(int $antal_hg, array $kod, array &$temp, array &$kod_halvgarderingar): void {
		/**
		 * Halvgardering.
		 */
		if ($antal_hg) {
			foreach ($kod as $nyckel => $tecken) {
				/**
				 * Skifta tecken efter odds.
				 * Mallar förutsätter 1X för halvgarderingar.
				 */
				match (true) {
					$this->reduktion[$this->halvgarderingar[$nyckel]][0] === '' =>
						$temp[$this->halvgarderingar[$nyckel]] = ($tecken === 0) ? 1 : 2, // skifta 1X -> X2
					$this->reduktion[$this->halvgarderingar[$nyckel]][1] === '' =>
						$temp[$this->halvgarderingar[$nyckel]] = ($tecken === 0) ? 0 : 2, // skifta 1X -> 12
					default =>
						$temp[$this->halvgarderingar[$nyckel]] = ($tecken === 0) ? 0 : 1 // behåll 1X
				};
			}
			$kod_halvgarderingar[] = $temp;
		}
	}
}
