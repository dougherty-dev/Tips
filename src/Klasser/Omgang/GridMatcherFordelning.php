<?php

/**
 * Klass GridMatcher.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Omgang;

use Tips\Klasser\Tips;
use Tips\Egenskaper\Eka;

/**
 * Klass GridMatcher.
 */
class GridMatcherFordelning extends GridMatcherMatchtabell {
	/**
	 * @param string[] $oddsstil
	 * @param string[] $streckstil
	 * @param int[] $rätt
	 */
	protected function stila_sannolikheter(int $index, array &$oddsstil, array &$streckstil, array &$rätt): void {
		if ($this->tips->utdelning->har_tipsrad) {
			$tecken = (int) $this->tips->utdelning->tipsrad_012[$index];

			$oddsstil[$tecken] = rstil(
				$this->tips->odds->sannolikheter[$index][$tecken],
				$this->tips->odds->maxsannolikheter[$index],
				$this->tips->odds->minsannolikheter[$index],
				'oddskolumn '
			);

			$streckstil[$tecken] = rstil(
				$this->tips->streck->sannolikheter[$index][$tecken],
				$this->tips->streck->maxsannolikheter[$index],
				$this->tips->streck->minsannolikheter[$index],
				'streckkolumn '
			);

			match ($this->tips->odds->maxsannolikheter[$index] === $this->tips->odds->sannolikheter[$index][$tecken]) {
				true => $rätt['odds']++,
				false => null
			};

			match ($this->tips->streck->maxsannolikheter[$index] === $this->tips->streck->sannolikheter[$index][$tecken]) {
				true => $rätt['streck']++,
				false => null
			};
		}
	}

	/**
	 * Beräkna fördelning av 1X2 per match i spelade rader.
	 * @param array<int, float[]> $fördelning
	 */
	protected function fördelning(&$fördelning): void {
		/**
		 * Fördelning av 1X2 i respektive spelade match.
		 */
		foreach ($this->tips->spelade->tipsvektor as $rad) {
			foreach (str_split($rad) as $index => $tecken) {
				$fördelning[$index][$tecken]++;
			}
		}

		$antal_spelade_rader = max(count($this->tips->spelade->tipsvektor), 1);

		/**
		 * Fördelning av 1X2 i respektive spelade match, procent.
		 */
		$fördelning = array_map(
			fn (array $odds): array =>
			array_map(fn (float $värde): float => $värde / $antal_spelade_rader, $odds),
			$fördelning
		);
	}
}
