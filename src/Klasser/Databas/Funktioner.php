<?php

/**
 * Klass Funktioner.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Databas;

/**
 * Klass Funktioner.
 */
class Funktioner extends Anslutning {
	/**
	 * KOntrollera databasens integritet.
	 */
	public function integritetskontroll(): string {
		$sats = $this->instans->query("PRAGMA integrity_check");
		return ($sats !== false) ? (string) $sats->fetchColumn() : '';
	}

	/**
	 * Optimera DB.
	 */
	private function dammsug(): void {
		foreach ([$this->temp, $this->instans] as $db) {
			$db->exec('VACUUM');
		}

		$this->logg->logga(self::class . ": ✅ Dammsög databaser.");
	}

	/**
	 * Spara kopia av DB.
	 */
	public function spara_backup(): void {
		if (defined('PHPUNIT')) {
			return;
		}

		$datum = date('Y-m-d');
		if (!file_exists(BACKUP . "/$datum" . '.db')) {
			if (copy(DB . '/tips.db', BACKUP . "/$datum" . '.db')) {
				$this->logg->logga(self::class . ": ✅ Sparade backup.");
				$this->dammsug();
				return;
			}

			$this->logg->logga(self::class . ": ❌ Kunde inte spara backup.");
			return;
		}

		$this->logg->logga(self::class . ": ✅ Backup existerar.");
	}
}
