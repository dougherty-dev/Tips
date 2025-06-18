<?php

/**
 * Klass SparaHistorik.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\JusteradePrediktioner;

/**
 * Klass SparaHistorik.
 */
class SparaHistorik extends HistorisktUtfall {
	/**
	 * Spara historik.
	 */
	public function spara_historik(): void {
		$this->historiskt_utfall();

		$justerade_odds = implode(',', [...$this->odds_j[0], ...$this->odds_j[1], ...$this->odds_j[2]]);
		$justerade_streck = implode(',', [...$this->streck_j[0], ...$this->streck_j[1], ...$this->streck_j[2]]);

		$this->db_preferenser->spara_preferens("prediktioner.historik", "$justerade_odds, $justerade_streck");

		$this->prediktioner->spel->db->logg->logga(self::class . ': ✅ Sparade historik.');
	}
}
