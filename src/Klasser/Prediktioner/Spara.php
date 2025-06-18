<?php

/**
 * Klass Spara.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Prediktioner;

use PDO;

/**
 * Klass Spara.
 */
class Spara extends Hamta {
	/**
	 * Spara prediktioner.
	 */
	public function spara_prediktioner(): void {
		$cstr = implode(', ', array_map(fn (int $i): string => "`p$i`", PLATT_ODDSMATRIS)); // `p1`, `p2`, …, `p39`
		$vstr = implode(', ', array_map(fn (int $i): string => ":p$i", PLATT_ODDSMATRIS)); // :p1, :p2, …, :p39

		$sats = $this->spel->db->instans->prepare("REPLACE INTO `{$this->tabell}`
			(`omgång`, `speltyp`, `komplett`, $cstr)
			VALUES (:omgang, :speltyp, :komplett, $vstr)");
		$sats->bindValue(':omgang', $this->spel->omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $this->spel->speltyp->value, PDO::PARAM_INT);
		$sats->bindValue(':komplett', $this->komplett, PDO::PARAM_BOOL);

		foreach (PLATT_ODDSMATRIS as $rad) {
			$prediktion = $rad - 1;
			[$i, $j, $k] = [floor($prediktion / 3), $prediktion % 3, $rad];
			$sats->bindValue(":p$k", $this->prediktioner[$i][$j], PDO::PARAM_STR);
		}

		$kommentar = match ($sats->execute()) {
			true => ": ✅ Sparade",
			false => ": ❌ Kunde inte spara"
		};
		$this->spel->db->logg->logga(self::class . "$kommentar {$this->tabell}. ({$this->spel->omgång})");
	}
}
