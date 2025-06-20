<?php

/**
 * Klass Rita.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Vinstspridning;

/**
 * Klass Rita.
 */
class Rita extends Permutera {
	protected string $bildfil = '';
	private const RADIE = 54;

	/**
	 * Rendera vinstspridning. Definiera en cirkel runt vinnande pixel,
	 * samt anslutande rader i vertikalt och horisontellt led.
	 */
	protected function rita(): void {
		[$x, $y] = $this->graf->tipsgrafskoordinater($this->tipsrad_012);
		$this->graf->sätt_cirkel($x, $y, self::RADIE, $this->graf->gul_v[3]);
		$this->graf->sätt_pixel($x, $y, $this->graf->blå); // 13 rätt i blå färg

		/**
		 * Permutera.
		 */
		foreach ($r12 = $this->permutera($this->tipsrad_012) as $x12) {
			foreach ($r11 = $this->permutera($x12) as $x11) {
				foreach ($this->permutera($x11) as $x10) {
					$this->rita_10r($x10, $r11, $r12);
				}

				$this->rita_11r($x11, $r12);
			}

			/**
			 * 12 rätt i lila färg.
			 */
			$this->pixla($x12, $this->graf->lila);
		}

		$this->graf->spara_tipsgraf($this->bildfil);
	}

	/**
	 * 10 rätt i svag grön nyans.
	 * @param string[] $r11
	 * @param string[] $r12
	 */
	private function rita_10r(string $x10, array $r11, array $r12): void {
		if (!in_array($x10, $r11, true) && !in_array($x10, $r12, true)) {
			$this->pixla($x10, $this->graf->grön_v[3]);
		}
	}

	/**
	 * 11 rätt i vit färg.
	 * @param string[] $r12
	 */
	private function rita_11r(string $x11, array $r12): void {
		if (!in_array($x11, $r12, true)) {
			$this->pixla($x11, $this->graf->vit);
		}
	}

	/**
	 * Rita enskild pixel.
	 */
	private function pixla(string $rad, int $färg): void {
		[$x, $y] = $this->graf->tipsgrafskoordinater($rad);
		$this->graf->sätt_pixel($x, $y, $färg);
	}
}
