<?php

/**
 * Klass TTReduceradKod.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

use Tips\Moduler\TT;

/**
 * Klass TTReduceradKod.
 * Reducera kod enligt reduktionskod.
 * R-system definieras i Koder.
 * Nyttjas av klasser Prova och Tackning.
 */
final class TTReduceradKod extends Reduktion {
	/**
	 * Initiera.
	 */
	public function __construct(protected TT $tt) {
		$this->beräkna_reducerad_kod();
	}

	/**
	 * Beräkna reducerad kod.
	 */
	private function beräkna_reducerad_kod(): void {
		$this->beräkna_garderingar();
		$antal_hg = count($this->halvgarderingar);

		/**
		 * Kräv överensstämmelse i garderingar med system.
		 */
		if (
			count($this->helgarderingar) != $this->tt->rkod->helgarderingar() ||
			$antal_hg != $this->tt->rkod->halvgarderingar()
		) {
			return;
		}

		/**
		 * Nollställ.
		 */
		$kod_halvgarderingar = [];
		$kod_helgarderingar = [];
		$nollrad = array_fill(0, $this->tt::TT_MATCHANTAL, 0);

		/**
		 * Reducera.
		 */
		foreach ($this->beräkna_reduktion() as $projektion) {
			foreach ($projektion as $index => $kodord) {
				$kod = array_map('intval', str_split($kodord));
				$temp = $nollrad;

				match ($index) {
					0 => $this->helgarderingar($kod, $temp, $kod_helgarderingar),
					default => $this->halvgarderingar($antal_hg, $kod, $temp, $kod_halvgarderingar)
				};
			}
		}

		$this->addera_kodord($antal_hg, $kod_helgarderingar, $kod_halvgarderingar);
	}

	/**
	 * Helgarderingar.
	 * @param int[] $kod
	 * @param int[] $temp
	 * @param array<int, int[]> $kod_helgarderingar
	 */
	private function helgarderingar(array $kod, array &$temp, array &$kod_helgarderingar): void {
		/**
		 * Helgardering.
		 * $projektion håller helgarderingar i [0], halvgarderingar i [1]
		 */
		foreach ($kod as $nyckel => $tecken) {
			$temp[$this->helgarderingar[$nyckel]] = $tecken;
		}

		$kod_helgarderingar[] = $temp;
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
					$this->tt->reduktion[$this->halvgarderingar[$nyckel]][0] === '' =>
						$temp[$this->halvgarderingar[$nyckel]] = $tecken === 0 ? 1 : 2, // skifta 1X -> X2
					$this->tt->reduktion[$this->halvgarderingar[$nyckel]][1] === '' =>
						$temp[$this->halvgarderingar[$nyckel]] = $tecken === 0 ? 0 : 2, // skifta 1X -> 12
					default =>
						$temp[$this->halvgarderingar[$nyckel]] = $tecken === 0 ? 0 : 1 // behåll 1X
				};
			}
			$kod_halvgarderingar[] = $temp;
		}
	}
}
