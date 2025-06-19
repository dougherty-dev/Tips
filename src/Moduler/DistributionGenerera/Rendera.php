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
		$data = [
			'10' => [10.0, $this->dist->graf->grön, 80],
			'5' => [5.0, $this->dist->graf->grön_v[7], 100],
			'3' => [3.0, $this->dist->graf->grön_v[5], 120],
			'2' => [2.0, $this->dist->graf->grön_v[3], 140],
			'1' => [1.0, $this->dist->graf->grön_v[2], 160],
			'0.5' => [0.5, $this->dist->graf->grön_v[1], 180]
		];

		foreach ($data as $index => $punkt) {
			if (!$this->andel_vid[$index] && $andel > $punkt[0]) {
				$this->rendera(
					$this->andel_vid[$index],
					$x,
					$punkt[1],
					$punkt[2],
					"$index % vid: $xkoord"
				);
			}
		}
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
