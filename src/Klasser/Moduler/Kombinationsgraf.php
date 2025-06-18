<?php

/**
 * Klass Kombinationsgraf.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Moduler;

use GdImage;
use Tips\Klasser\Graf;

/**
 * Klass Kombinationsgraf.
 */
class Kombinationsgraf extends Initiera {
	/**
	 * Rita och spara kombinerad graf för olika grafmoduler.
	 */
	public function skapa_kombinationsgraf(): void {
		$filer = [];
		foreach ($this->m_moduler as $modul) {
			if (property_exists($modul, 'kombinationsgraf')) {
				$filer[] = $modul->kombinationsgraf;
			}
		}

		sort($filer);
		$bild = new Graf();
		foreach ($filer as $bild2) {
			if (is_file($filnamn = GRAF . "/$bild2")) {
				/** @var GdImage $png */
				$png = imagecreatefrompng($filnamn);
				imagecopymerge($bild->graf, $png, 0, 0, 0, 0, $bild->bredd, $bild->höjd, 40);
			}
		}

		$bild->spara_tipsgraf('/kombination.png');
		$this->utdelning->spel->db->logg->logga(self::class . ': ✅ Skapade kombinationsgraf.');
	}
}
