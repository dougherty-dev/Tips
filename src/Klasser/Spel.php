<?php

/**
 * Klass Spel.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

use PDO;
use Tips\Klasser\Spel\Omgang;

/**
 * Klass Spel.
 */
final class Spel extends Omgang {
	/**
	 * Uppdatera konstruktor.
	 */
	public function __construct() {
		parent::__construct();
		$this->hämta_spel();
	}

	/**
	 * Hämta aktuellt spel från databas.
	 */
	private function hämta_spel(): void {
		$sats = $this->db->instans->query("SELECT `omgång`, `speltyp`, `sekvens`
			FROM `spel` WHERE `aktiv` LIMIT 1");
		if ($sats !== false) {
			foreach ($sats->fetchAll(PDO::FETCH_ASSOC) as $rad) {
				$this->speltyp = Speltyp::tryFrom($rad['speltyp']) ?? Speltyp::Stryktipset;
				$this->omgång = $rad['omgång'];
				$this->sekvens = $rad['sekvens'];
				$this->hämta_sekvenser();
			}
		}

		if ($sats === false) {
			$this->senaste_omgång();
		}

		if ($this->spel_finns) {
			$this->spel_komplett();
			$this->filnamn = $this->filer->filnamn($this->omgång, $this->speltyp, $this->sekvens);
		}
	}
}
