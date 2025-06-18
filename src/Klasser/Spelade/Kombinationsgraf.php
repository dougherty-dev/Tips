<?php

/**
 * Klass Kombinationsgraf.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Spelade;

use Tips\Klasser\Graf;

/**
 * Klass Kombinationsgraf.
 */
class Kombinationsgraf extends Tipsgraf {
	/**
	 * Rita kombinationsgraf och spara bildfil.
	 */
	public function kombinationsgraf(string $fil, string $bildfil): string {
		$bild = new Graf();
		$graf = imagecreatefrompng(GRAF . '/kombination.png');
		if ($graf !== false) {
			$bild->graf = $graf;
		}

		if (is_file(GRAF . $bildfil)) {
			$överlagring = imagecreatefrompng(GRAF . $bildfil);
			($överlagring !== false) and imagecopymerge($bild->graf, $överlagring, 0, 0, 0, 0, $bild->bredd, $bild->höjd, 50);
		}

		$bild->spara_tipsgraf($fil);
		return $bild->rendera_tipsgraf($fil);
	}
}
