<?php

/**
 * Klass Filer.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

use PDO;

/**
 * Klass Filer.
 */
final class Filer {
	public function __construct(public Databas $db) {
	}

	/**
	 * Definiera filnamn.
	 */
	public function filnamn(int $omgång, Speltyp $speltyp, int $sekvens): string {
		return $speltyp->produktnamn() . '-t' . $speltyp->value . '-o' . $omgång . '-s' . $sekvens;
	}

	/**
	 * Definiera mappnamn.
	 */
	public function mappnamn(int $omgång, Speltyp $speltyp): string {
		$år = 0;
		$sats = $this->db->instans->prepare('SELECT `år` FROM `utdelning`
			WHERE `omgång`=:omgang AND `speltyp`=:speltyp LIMIT 1');
		$sats->bindValue(':omgang', $omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $speltyp->value, PDO::PARAM_INT);
		$sats->bindColumn('år', $år, PDO::PARAM_INT);
		$sats->execute();

		if ($sats->fetch(PDO::FETCH_OBJ) !== false) {
			return "/$år/" . $speltyp->produktnamn();
		}

		return '';
	}
}
