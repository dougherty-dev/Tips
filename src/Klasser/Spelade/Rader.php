<?php

/**
 * Klass Rader.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Spelade;

use Tips\Klasser\Vinstspridning;

/**
 * Klass Rader.
 */
class Rader extends Kombinationsgraf {
	/**
	 * Visa snabbrad.
	 * Behändig textsammanfattning av en slumprad.
	 */
	protected function snabbrad(): string {
		$antal_rader = count($this->tipsvektor);

		$snabbrad = '';
		if ($antal_rader <= 0) {
			return $snabbrad;
		}

		$rad = siffror_till_symboler($this->tipsvektor[mt_rand(0, $antal_rader - 1)]);

		foreach ($this->matcher->match as $index => $match) {
			$matchnr = $index + 1;
			/**
			 * Dela rader efter tre matcher, men behåll sista.
			 */
			$radbrytning = match (true) {
				$matchnr % 3 == 0 && $matchnr < 12 => '<br>',
				default => ''
			};

			$snabbrad .= t(7, "$matchnr $match {$rad[$index]}<br>$radbrytning");
		}

		return rtrim($snabbrad);
	}

	/**
	 * Spelade rader.
	 */
	protected function spelade_rader(): string {
		$spelade_rader = '';
		foreach ($this->tipsvektor as $rad) {
			$tipsrad = siffror_till_symboler($rad);
			$spelade_rader .= t(7, "<code>$tipsrad</code><br>");
		}

		return $spelade_rader;
	}
}
