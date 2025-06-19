<?php

/**
 * Klass Kontrollera.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Moduler;

use PDO;
use PDOStatement;

/**
 * Klass Kontrollera.
 */
class Kontrollera extends Annonsera {
	/**
	 * Kontrollera nya moduler.
	 */
	public function kontrollera_nya_moduler(): void {
		/**
		 * Denna kontroll har ingen mening vid generering.
		 */
		if (defined("GENERERA")) {
			return;
		}

		$moduler = []; // aktiva moduler
		$modulbeteckning = []; // modulnamn

		$glob = (array) glob(MODULER . '/*.php');
		$befintliga_moduler = array_map(fn ($filnamn): string => basename((string) $filnamn, '.php'), $glob);

		$index = 1;
		/** @var PDOStatement $sats */
		$sats = $this->utdelning->spel->db->instans->query('SELECT * FROM `moduler` ORDER BY `prioritet`');

		foreach ($sats->fetchAll(PDO::FETCH_ASSOC) as $rad) {
			if (isset($_REQUEST['uppdatera_moduler'])) {
				$rad['aktiv'] = isset($_REQUEST['modul'][$rad['namn']]) &&
					$_REQUEST['modul'][$rad['namn']]['aktiv'] === '1' ? 1 : 0;
			}

			if (in_array($rad['namn'], $befintliga_moduler, true)) {
				$moduler[] = [$index, $rad['namn'], $rad['aktiv']];
				$modulbeteckning[] = $rad['namn'];
				$index++;
			}
		}

		$antal_moduler = count($modulbeteckning);
		$nya_moduler = array_diff($befintliga_moduler, $modulbeteckning);

		/**
		 * Lägg till nya moduler i filsystem.
		 */
		foreach ($nya_moduler as $ny_modul) {
			$moduler[] = [$antal_moduler++, $ny_modul, 0];
		}

		/**
		 * Nyskapa struktur.
		 */
		$this->utdelning->spel->db->instans->query("DELETE FROM `moduler`");

		foreach ($moduler as $modul) {
			$sats = $this->utdelning->spel->db->instans->prepare("INSERT INTO `moduler`
				(`prioritet`, `namn`, `aktiv`) VALUES (:prioritet, :namn, :aktiv)");
			$sats->bindValue(':prioritet', $modul[0], PDO::PARAM_INT);
			$sats->bindValue(':namn', $modul[1], PDO::PARAM_STR);
			$sats->bindValue(':aktiv', $modul[2], PDO::PARAM_BOOL);
			$sats->execute();
		}
	}
}
