<?php

/**
 * Klass Databas.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

use PDO;
use Tips\Klasser\Databas\Funktioner;

/**
 * Klass Databas.
 */
final class Databas extends Funktioner {
	/**
	 * Radera omgång.
	 */
	public function radera_omgång(string $tabell, int $omgång, Speltyp $speltyp): void {
		$sats = $this->instans->prepare("DELETE FROM `{$tabell}`
			WHERE `omgång`=:omgang AND `speltyp`=:speltyp");
		$sats->bindValue(':omgang', $omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $speltyp->value, PDO::PARAM_INT);
		match ($sats->execute()) {
			true => $this->logg->logga(self::class . ": ✅ Raderade omgång. ({$omgång})"),
			default => $this->logg->logga(self::class . ": ❌ Kunde inte radera omgång. ({$omgång})")
		};
	}

	/**
	 * Radera sekvens.
	 */
	public function radera_sekvens(string $tabell, int $omgång, Speltyp $speltyp, int $sekvens): void {
		$sats = $this->instans->prepare("DELETE FROM `{$tabell}`
			WHERE `omgång`=:omgang AND `speltyp`=:speltyp AND `sekvens`=:sekvens");
		$sats->bindValue(':omgang', $omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $speltyp->value, PDO::PARAM_INT);
		$sats->bindValue(':sekvens', $sekvens, PDO::PARAM_INT);
		match ($sats->execute()) {
			true => $this->logg->logga(self::class . ": ✅ Raderade sekvens. ({$omgång}-{$sekvens}–{$tabell})"),
			default => $this->logg->logga(self::class . ": ❌ Kunde inte radera sekvens. ({$omgång}-{$sekvens}–{$tabell})")
		};
	}
}
