<?php

/**
 * Klass Graf.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

use Tips\Klasser\Graf\Koordinater;

/**
 * Klass Graf.
 */
final class Graf extends Koordinater {
	/**
	 * Uppdatera konstruktor.
	 */
	public function __construct(int $bredd = 2187, int $höjd = 729) {
		parent::__construct($bredd, $höjd);
		$this->initiera_graf();
	}

	/**
	 * Initiera graf.
	 */
	private function initiera_graf(): void {
		$this->graf = imagecreatetruecolor(max(1, $this->bredd), max(1, $this->höjd));

		$this->gul = (int) imagecolorallocate($this->graf, 255, 255, 0);
		$this->röd = (int) imagecolorallocate($this->graf, 255, 0, 0);
		$this->grön = (int) imagecolorallocate($this->graf, 0, 255, 0);
		$this->blå = (int) imagecolorallocate($this->graf, 0, 255, 255);
		$this->lila = (int) imagecolorallocate($this->graf, 255, 0, 255);
		$this->vit = (int) imagecolorallocate($this->graf, 255, 255, 255);
		$this->svart = (int) imagecolorallocate($this->graf, 0, 0, 0);
		for ($index = 0; $index <= 9; $index++) {
			$nyans = 5 + 25 * $index;
			$this->gul_v[$index] = (int) imagecolorallocate($this->graf, $nyans, $nyans, 0);
			$this->röd_v[$index] = (int) imagecolorallocate($this->graf, $nyans, 0, 0);
			$this->grön_v[$index] = (int) imagecolorallocate($this->graf, 0, $nyans, 0);
		}
	}
}
