<?php

/**
 * Klass Parallellisering.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Parallellisering;

use PDO;

/**
 * Klass Parallellisering.
 */
class Behandla extends Databas {
	/**
	 * Behandla parallellisering.
	 * @return string[]
	 */
	public function behandla_parallellisering(): array {
		$this->vänta_på_databas();

		$vektorer = [];
		$sats = $this->spel->db->temp->query("SELECT `val` FROM `parallellisering`");
		if ($sats !== false) {
			foreach ($sats->fetchAll(PDO::FETCH_ASSOC) as $rad) {
				if ($rad['val'] !== '') {
					array_push($vektorer, ...explode(',', $rad['val']));
				}
			}
		}

		$this->spel->db->temp->exec("DELETE FROM `parallellisering`");
		return $vektorer;
	}
}
