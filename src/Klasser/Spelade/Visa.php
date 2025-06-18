<?php

/**
 * Klass Visa.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Spelade;

/**
 * Klass Visa.
 */
class Visa extends Mall {
	public bool $spelad = false;

	/**
	 * Visa genererade rader.
	 */
	public function visa_genererade_rader(): void {
		if (file_exists(GRAF . $this->bildfil)) {
			$this->tipsgraf = $this->graf->rendera_tipsgraf($this->bildfil);
		}

		$radknapp = '';
		$kombinationsgraf = '';

		/**
		 * Spel finns för omgången.
		 */
		if ($this->spelad) {
			/**
			 * Lägg rader som sträng i request.
			 */
			$kod = base64_encode("{$this->mappnamn}/{$this->utdelning->spel->filnamn}.txt");

			$radknapp = t(7, "<p><a id=\"radknapp\" target=\"_blank\" href=\"/ajax/RaderAjax.php?fil=$kod\">{$this->antal_utvalda_rader} r ↗️</a></p>");

			/**
			 * Återskapa tipsrader i filsystemet.
			 */
			if (!file_exists($this->textfil)) {
				$this->spara_genererade_tipsrader_fil();
			}

			$kombinationsgraf = $this->kombinationsgraf('/kombinationsgraf.png', $this->bildfil);
		}

		$this->markup($radknapp, $kombinationsgraf);
	}
}
