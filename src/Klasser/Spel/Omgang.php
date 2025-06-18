<?php

/**
 * Klass Omgang.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Spel;

use PDO;
use Tips\Klasser\Speltyp;

/**
 * Klass Omgang.
 */
class Omgang extends Spara {
	/**
	 * Hämta senaste omgång.
	 */
	public function senaste_omgång(): void {
		$this->sekvens = -1;
		$sats = $this->db->instans->prepare('SELECT `omgång` FROM `matcher`
			WHERE `speltyp`=:speltyp ORDER BY `omgång` DESC LIMIT 1');
		$sats->bindValue(':speltyp', $this->speltyp->value, PDO::PARAM_INT);
		$sats->bindColumn('omgång', $this->omgång, PDO::PARAM_INT);
		$sats->execute();

		if ($sats->fetchColumn() !== false) {
			$sats->closeCursor();
			$this->hämta_sekvenser();
			$this->spara_spel();
		}
	}

	/**
	 * Radera omgång.
	 */
	public function radera_omgång(int $omgång, Speltyp $speltyp): void {
		$tabeller = ['spel', 'matcher', 'odds', 'streck', 'utdelning', 'spelade'];
		foreach ($tabeller as $tabell) {
			$this->db->radera_omgång($tabell, $omgång, $speltyp);
		}

		$this->senaste_omgång();
		$this->db->logg->logga(self::class . ": ✅ Raderade omgång. ($omgång)");
	}
}
