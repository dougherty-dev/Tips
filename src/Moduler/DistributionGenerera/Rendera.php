<?php

/**
 * Klass Rendera.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\DistributionGenerera;

/**
 * Klass Rendera.
 */
class Rendera extends Oddssummor {
	/**
	 * Rita procentsatser med linjer i gröna nyanser.
	 */
	protected function percentage_lines(float $andel, int $x, string $xkoord): void {
		match (true) {
			!$this->andel_vid['10'] && $andel > 10.0 =>
				$this->rendera($this->andel_vid['10'], $x, $this->dist->graf->grön, 80, "10 % vid: $xkoord"),
			!$this->andel_vid['5'] && $andel > 5.0 =>
				$this->rendera($this->andel_vid['5'], $x, $this->dist->graf->grön_v[7], 100, "5 % vid: $xkoord"),
			!$this->andel_vid['3'] && $andel > 3.0 =>
				$this->rendera($this->andel_vid['3'], $x, $this->dist->graf->grön_v[5], 120, "3 % vid: $xkoord"),
			!$this->andel_vid['2'] && $andel > 2.0 =>
				$this->rendera($this->andel_vid['2'], $x, $this->dist->graf->grön_v[3], 140, "2 % vid: $xkoord"),
			!$this->andel_vid['1'] && $andel > 1.0 =>
				$this->rendera($this->andel_vid['1'], $x, $this->dist->graf->grön_v[2], 160, "1 % vid: $xkoord"),
			!$this->andel_vid['0.5'] && $andel > 0.5 =>
				$this->rendera($this->andel_vid['0.5'], $x, $this->dist->graf->grön_v[1], 180, "0.5 % vid: $xkoord"),
			default => null
		};
	}

	/**
	 * Rendera linjer och text.
	 */
	private function rendera(bool &$andel, int $x, int $färg, int $y, string $text): void {
		$andel = true;
		$this->dist->graf->sätt_linje($x, 0, $x, $this->dist->graf->höjd, $färg);
		$this->dist->graf->sätt_text(20, $y, $text, $this->dist->graf->grön);
	}
}
