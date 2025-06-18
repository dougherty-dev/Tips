<?php

/**
 * Klass Komplett.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Spel;

use PDO;

/**
 * Klass Komplett.
 */
class Komplett extends Sekvenser {
	public bool $komplett = false;

	/**
	 * Avgör om spel är komplett.
	 */
	protected function spel_komplett(): void {
		$hämtad_omgång = 0;
		$sats = $this->db->instans->prepare("SELECT `odds`.`omgång` AS `hämtad_omgång` FROM `odds`
			LEFT JOIN `streck` ON `odds`.`omgång`=`streck`.`omgång`
			LEFT JOIN `matcher` ON `odds`.`omgång`=`matcher`.`omgång`
			WHERE `odds`.`komplett` AND `streck`.`komplett` AND `matcher`.`komplett`
			AND `odds`.`omgång`=:omgang AND `odds`.`speltyp`=:speltyp");
		$sats->bindValue(':omgang', $this->omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $this->speltyp->value, PDO::PARAM_INT);
		$sats->bindColumn('hämtad_omgång', $hämtad_omgång, PDO::PARAM_INT);
		$sats->execute();
		$sats->fetch(PDO::FETCH_OBJ);
		$this->komplett = $this->omgång === $hämtad_omgång;
	}

	/**
	 * Kontrollera att omgång existerar.
	 */
	public function omgång_existerar(int $omgång): bool {
		$sats = $this->db->instans->prepare('SELECT `omgång` FROM `spel`
			WHERE `omgång`=:omgang AND `speltyp`=:speltyp LIMIT 1');
		$sats->bindValue(':omgang', $omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $this->speltyp->value, PDO::PARAM_INT);
		$sats->execute();
		$kolumn = $sats->fetchColumn();
		$sats->closeCursor();
		return $kolumn === $omgång;
	}
}
