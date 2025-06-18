<?php

/**
 * Klass Radera.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\System;

use Tips\Klasser\Speltyp;

/**
 * Klass Radera.
 */
class Radera extends Hamta {
	/**
	 * Radera omgång.
	 */
	public function radera_omgång(int $omgång, Speltyp $speltyp): void {
		$this->odds->spel->db->radera_omgång('system', $omgång, $speltyp);
		$this->odds->spel->db->logg->logga(self::class . ": ✅ Raderade system. ({$this->odds->spel->omgång})");
	}

	/**
	 * Radera sekvens.
	 */
	public function radera_sekvens(int $omgång, Speltyp $speltyp, int $sekvens): void {
		$this->odds->spel->db->radera_sekvens('system', $omgång, $speltyp, $sekvens);
		$this->odds->spel->db->logg->logga(self::class . ": ✅ Raderade system. ({$this->odds->spel->omgång}-{$this->odds->spel->sekvens})");
	}
}
