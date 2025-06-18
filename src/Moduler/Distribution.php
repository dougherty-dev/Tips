<?php

/**
 * Klass Distribution.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler;

use ReflectionClass;
use Tips\Klasser\Tips;
use Tips\Moduler\DistributionGenerera\Generera;
use Tips\Moduler\Distribution\Visa;

/**
 * Klass Distribution.
 */
final class Distribution extends Visa {
	/**
	 * Visa modul.
	 */
	public function visa_modul(): void {
		$klass = (new ReflectionClass($this))->getShortName();

		/**
		 * Generera ny statistik vid behov.
		 */
		$this->historik = $this->db_preferenser->hämta_preferens('distribution.historik');
		if ($this->historik === '') {
			$this->historik();
		}

		$oddssumma = number_format($this->oddssumma, 2);

		$distgraf = '';
		if (file_exists(GRAF . $this->bildfil)) {
			$distgraf = $this->graf->rendera_tipsgraf($this->bildfil);
		} elseif ($this->odds->komplett && $this->streck->komplett) {
			$tips = new Tips($this->odds->spel);
			$this->spara_omgång($tips);

			if (file_exists(GRAF . $this->bildfil)) {
				$distgraf = $this->graf->rendera_tipsgraf($this->bildfil);
			}
		}

		$this->visa($klass, $oddssumma, $distgraf);
	}

	/**
	 * Spara omgång.
	 */
	public function spara_omgång(Tips $tips): void {
		$this->historik();
		$this->odds->spel->db->logg->logga(self::class . ': ✅ Sparade historik.');

		new Generera($tips, $this);
		$this->odds->spel->db->logg->logga(self::class . ': ✅ Genererade distribution.');
	}
}
