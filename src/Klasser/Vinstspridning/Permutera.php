<?php

/**
 * Klass Permutera.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Vinstspridning;

use Tips\Klasser\Graf;

/**
 * Klass Permutera.
 */
class Permutera {
	protected Graf $graf;

	public function __construct(protected string $tipsrad_012) {
	}

	/**
	 * Permutera alla möjliga kombinationer.
	 * @return string[]
	 */
	protected function permutera(string $tipsrad): array {
		$ursprunglig_tipsrad = $tipsrad;
		$rad = [];

		for ($i = 0; $i < MATCHANTAL; $i++) {
			foreach (array_diff(TECKENRYMD, [(int) $tipsrad[$i]]) as $tecken) {
				$tipsrad[$i] = "$tecken";
				if ($tipsrad !== $this->tipsrad_012) {
					$rad[] = $tipsrad;
				}
			}

			$tipsrad = $ursprunglig_tipsrad;
		}

		return $rad;
	}
}
