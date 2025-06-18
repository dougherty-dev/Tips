<?php

/**
 * Klass Prediktionsdata.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Prediktioner;

use PDO;

/**
 * Klass Prediktionsdata.
 */
class Prediktionsdata extends Sannolikheter {
	/**
	 * Hämta prediktioner för alla omgångar.
	 * @return array<string, array<int, float[]>>
	 */
	public function prediktionsdata(string $tabell, int $u13_min = 0, int $u13_max = MAXVINST): array {
		$prediktionsdata = [];
		$pred = implode(', ', array_map(fn (int $i): string => "`p$i`", PLATT_ODDSMATRIS)); // `p1`, `p2`, …, `p39`
		$sats = $this->spel->db->instans->prepare("SELECT $pred, `omgång` FROM `$tabell` NATURAL JOIN `utdelning`
			WHERE `$tabell`.`komplett` AND `tipsrad_012` AND `u13` BETWEEN :u13_min AND :u13_max ORDER BY `omgång`");
		$sats->bindValue(':u13_min', $u13_min, PDO::PARAM_INT);
		$sats->bindValue(':u13_max', $u13_max, PDO::PARAM_INT);
		$sats->execute();
		foreach ($sats->fetchAll(PDO::FETCH_ASSOC) as $rad) {
			$prediktionsdata[(string) $rad['omgång']] =
				array_chunk(array_map(fn (int $n): float => $rad["p$n"], PLATT_ODDSMATRIS), 3);
		}
		return $prediktionsdata;
	}
}
