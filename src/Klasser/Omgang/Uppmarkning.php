<?php

/**
 * Klass Uppmarkning.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Omgang;

use Tips\Klasser\Tips;
use Tips\Klasser\Preferenser;
use Tips\Klasser\Investera;
use Tips\Klasser\Scheman;
use Tips\Klasser\Generera;

/**
 * Klass Uppmarkning.
 */
final class Uppmarkning {
	/**
	 * Initiera.
	 */
	public function __construct(private Tips $tips) {
		$this->märk_upp();
	}

	/**
	 * Rendera HTML.
	 */
	private function märk_upp(): void {
		new Initiera($this->tips);

		if (isset($_REQUEST['generera'])) {
			new Generera($this->tips);
		}

		new Visa($this->tips);
		$this->tips->spelade->visa_genererade_rader();
		new Preferenser($this->tips->spel->db);
		(new Investera($this->tips))->visa_investering();
		new Scheman($this->tips);

		if ($this->tips->spel->spel_finns) {
			$this->tips->moduler->visa_moduler();
		}

		new Terminera($this->tips);
	}
}
