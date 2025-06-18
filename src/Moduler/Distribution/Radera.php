<?php

/**
 * Klass Radera.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Distribution;

use Tips\Klasser\Speltyp;
use Tips\Moduler\DistributionGenerera\Generera;

/**
 * Klass Radera.
 */
class Radera extends Hamta {
	/**
	 * Radera omgång.
	 */
	public function radera_omgång(int $omgång, Speltyp $speltyp): void {
		$this->odds->spel->db->radera_omgång('distribution', $omgång, $speltyp);
		$this->odds->spel->db->logg->logga(self::class . ": ✅ Raderade distribution. ({$this->odds->spel->omgång})");

		$mönster = "*-t{$speltyp->value}-o$omgång-*";
		$glob = (array) glob(GRAF . DISTRIBUTION . "/$mönster.png");
		foreach ($glob as $fil) {
			is_string($fil) and $this->radera_fil($fil);
		}
	}

	/**
	 * Radera sekvens.
	 */
	public function radera_sekvens(int $omgång, Speltyp $speltyp, int $sekvens): void {
		$this->odds->spel->db->radera_sekvens('distribution', $omgång, $speltyp, $sekvens);
		$filnamn = $this->odds->spel->filer->filnamn($omgång, $speltyp, $sekvens);

		$fil = GRAF . DISTRIBUTION . "/$filnamn.png";
		$this->radera_fil($fil);
	}

	/**
	 * Radera fil.
	 */
	private function radera_fil(string $fil): void {
		$kommentar = match (file_exists($fil) && unlink($fil)) {
			true => ": ✅ Raderade fil",
			false => ": ❌ Kunde inte radera fil"
		};
		$this->odds->spel->db->logg->logga(self::class . "$kommentar $fil");
	}
}
