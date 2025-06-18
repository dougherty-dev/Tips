<?php

/**
 * Klass Tipsdata.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Prediktioner;

use PDO;

/**
 * Klass Tipsdata.
 */
class Tipsdata extends Prediktionsdata {
	/**
	 * Hämta tipsdata för alla omgångar.
	 * @return array<string, string>
	 */
	public function tipsdata(int $u13_min = 0, int $u13_max = MAXVINST): array {
		$tipsdata = [];
		$sats = $this->spel->db->instans->prepare("SELECT `omgång`, `tipsrad_012` FROM `utdelning`
			WHERE `tipsrad_012` AND `u13` BETWEEN :u13_min AND :u13_max ORDER BY `omgång`");
		$sats->bindValue(':u13_min', $u13_min, PDO::PARAM_INT);
		$sats->bindValue(':u13_max', $u13_max, PDO::PARAM_INT);
		$sats->execute();
		foreach ($sats->fetchAll(PDO::FETCH_ASSOC) as $r) {
			$tipsdata[(string) $r['omgång']] = $r['tipsrad_012'];
		}
		return $tipsdata;
	}
}
