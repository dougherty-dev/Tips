<?php

/**
 * Klass Radera.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Spelade;

use Tips\Klasser\Speltyp;

/**
 * Klass Radera.
 */
class Radera extends Visa {
	/**
	 * Radera sekvens i databas och tillhörande filer.
	 * Nyttja mönster i filnamn.
	 */
	public function radera_sekvens(int $omgång, Speltyp $speltyp, int $sekvens): void {
		$this->utdelning->spel->db->radera_sekvens('spelade', $omgång, $speltyp, $sekvens);
		$this->radera_filer("*-t{$speltyp->value}-o$omgång-s$sekvens", $omgång, $speltyp);
	}

	/**
	 * Radera filer för omgång.
	 */
	public function radera_omgång(int $omgång, Speltyp $speltyp): void {
		$this->radera_filer("*-t{$speltyp->value}-o$omgång-*", $omgång, $speltyp);
	}

	/**
	 * Radera spelade och genererade filer från filsystem.
	 */
	public function radera_filer(string $mönster, int $omgång, Speltyp $speltyp): void {
		$glob = (array) glob(GRAF . SPELADE . "/$mönster.png");
		foreach ($glob as $fil) {
			$this->radera_fil($fil);
		}

		$mapp = $this->utdelning->spel->filer->mappnamn($omgång, $speltyp);
		$glob = (array) glob(BAS . GENERERADE . "$mapp/$mönster.txt");
		foreach ($glob as $fil) {
			$this->radera_fil($fil);
		}
	}

	/**
	 * Radera enskild fil.
	 */
	private function radera_fil(string|false $fil): void {
		if (!is_string($fil) || !file_exists($fil)) {
			return;
		}

		$kommentar = match (unlink($fil)) {
			true => ": ✅ Raderade fil",
			false => ": ❌ Kunde inte radera fil"
		};

		$this->utdelning->spel->db->logg->logga(self::class . "$kommentar $fil");
	}
}
