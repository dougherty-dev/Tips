<?php

/**
 * Klass Spara.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Spel;

use PDO;

/**
 * Klass Spara.
 */
class Spara extends Komplett {
	public string $filnamn = '';

	/**
	 * Spara spel.
	 */
	public function spara_spel(): void {
		if ($this->omgång <= 0 || $this->sekvens <= 0) {
			return;
		}

		$this->spel_komplett();

		$sats = $this->db->instans->query("UPDATE `spel` SET `aktiv`=0 WHERE `aktiv`");

		/**
		 * Delete och insert istället för update.
		 */
		$sats = $this->db->instans->prepare("REPLACE INTO `spel`
			(`omgång`, `speltyp`, `sekvens`, `aktiv`)
			VALUES (:omgang, :speltyp, :sekvens, :aktiv)");
		$sats->bindValue(':omgang', $this->omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $this->speltyp->value, PDO::PARAM_INT);
		$sats->bindValue(':sekvens', $this->sekvens, PDO::PARAM_INT);
		$sats->bindValue(':aktiv', true, PDO::PARAM_BOOL);

		if (!$sats->execute()) {
			$this->db->logg->logga(self::class . ": ❌ Kunde inte spara spel. ({$this->omgång}-{$this->sekvens})");
			return;
		}

		$this->filnamn = $this->filer->filnamn($this->omgång, $this->speltyp, $this->sekvens);
		$this->db->logg->logga(self::class . ": ✅ Sparade spel. ({$this->omgång}-{$this->sekvens})");
	}
}
