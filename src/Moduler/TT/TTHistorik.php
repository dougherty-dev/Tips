<?php

/**
 * Klass TTHistorik.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

use Tips\Moduler\TT;

/**
 * Klass TTHistorik.
 */
final class TTHistorik extends HistorisktUtfall {
	/**
	 * Hämta historik från databas.
	 */
	public function hämta_historik(): void {
		$historik = $this->tt->db_preferenser->hämta_preferens("topptips.historik");
		if ($historik === '') {
			$this->historiskt_utfall();
			return;
		}

		$this->tt->utfallshistorik = [];
		foreach (array_chunk(explode(',', $historik), 4) as $v) {
			$this->tt->utfallshistorik[] = array_map('floatval', $v);
		}
	}
}
