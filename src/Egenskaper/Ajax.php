<?php

/**
 * Egenskap Ajax.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Egenskaper;

use Tips\Klasser\Databas;
use Tips\Klasser\DBPreferenser;

/**
 * Egenskap Ajax.
 */
trait Ajax {
	protected Databas $db;
	protected DBPreferenser $db_preferenser;

	/**
	 * Exekvera metod per indata.
	 */
	private function förgrena(): void {
		$this->db = new Databas();
		$this->db_preferenser = new DBPreferenser($this->db);
		match ($nyckel = array_key_first($_REQUEST)) {
			$nyckel => method_exists($this, "$nyckel") and $this->$nyckel(),
			default => $this->db->logg->logga(self::class . ": ❌ Kunde inte förgrena. _REQUEST: $nyckel")
		};
	}

	/**
	 * Ändra faktor för urvalsfrekvens.
	 */
	private function ändra_attraktionsfaktor(string $modul): void {
		$this->db_preferenser->validera_indata('attraktionsfaktor', AF_MIN, AF_MAX, AF_STD, "$modul.attraktionsfaktor");
	}
}
