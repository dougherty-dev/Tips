<?php

/**
 * Klass Preferenser.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Autospik;

use Tips\Egenskaper\Tick;
use Tips\Moduler\Autospik\Konstanter;

/**
 * Klass Preferenser.
 */
class Preferenser extends Historik {
	use Tick;
	use Konstanter;

	/**
	 * Uppdatera preferenser.
	 */
	protected function uppdatera_preferenser(): void {
		$this->db_preferenser->int_preferens_i_intervall(
			$this->valda_spikar,
			self::AS_MIN,
			self::AS_MAX,
			self::AS_STD,
			'autospik.valda_spikar'
		);

		$this->db_preferenser->int_preferens_i_intervall(
			$this->attraktionsfaktor,
			AF_MIN,
			AF_MAX,
			AF_STD,
			'autospik.attraktionsfaktor'
		);

		$this->historik = $this->db_preferenser->hämta_preferens('autospik.historik');
		if ($this->historik === '') {
			$this->autospik_historik();
		}
	}
}
