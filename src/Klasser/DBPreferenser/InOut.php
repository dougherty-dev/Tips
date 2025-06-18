<?php

/**
 * InOut class.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\DBPreferenser;

use PDO;
use Tips\Klasser\Databas;

/**
 * InOut class.
 */
class InOut {
	/**
	 * Instantiation.
	 */
	public function __construct(public Databas $db) {
	}

	/**
	 * Get preference from DB.
	 */
	public function hämta_preferens(string $namn, string $db = 'instans'): string {
		$värde = '';
		$sats = $this->db->$db->prepare("SELECT `värde` FROM `preferenser` WHERE `namn`=:namn LIMIT 1");
		$sats->bindValue(':namn', $namn, PDO::PARAM_STR);
		$sats->bindColumn('värde', $värde, PDO::PARAM_STR);
		$sats->execute();
		$sats->fetch(PDO::FETCH_OBJ);
		$sats->closeCursor();
		return $värde;
	}

	/**
	 * Save preference from DB.
	 */
	public function spara_preferens(string $namn, string $värde, string $db = 'instans'): void {
		$sats = $this->db->$db->prepare("REPLACE INTO `preferenser` (`namn`, `värde`) VALUES (:namn, :varde)");
		$sats->bindValue(':namn', $namn, PDO::PARAM_STR);
		$sats->bindValue(':varde', $värde, PDO::PARAM_STR);
		$sats->execute();
	}

	/**
	 * Does preference exist in DB?
	 */
	public function preferens_finns(string $namn, string $db = 'instans'): bool {
		$värde = '';
		$existerar = false;
		$sats = $this->db->$db->prepare("SELECT EXISTS(SELECT `värde` FROM `preferenser` WHERE `namn`=:namn LIMIT 1) AS `existerar`");
		$sats->bindValue(':namn', $namn, PDO::PARAM_STR);
		$sats->bindColumn('värde', $värde, PDO::PARAM_STR);
		$sats->bindColumn('existerar', $existerar, PDO::PARAM_BOOL);
		$sats->execute();
		$sats->fetch(PDO::FETCH_OBJ);
		return $existerar;
	}
}
