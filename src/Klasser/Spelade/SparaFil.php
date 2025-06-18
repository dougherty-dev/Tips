<?php

/**
 * Klass SparaFil.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Spelade;

/**
 * Klass SparaFil.
 */
class SparaFil extends Spara {
	/**
	 * Spara genererade rader i fil.
	 */
	public function spara_genererade_tipsrader_fil(): void {
		$tipsrader = ucfirst($this->utdelning->spel->speltyp->produktnamn()) . "\r\n";
		$tipsrader .= implode("\r\n", array_map(fn ($rad): string => 'E,' . kommatisera($rad), $this->tipsvektor));

		/** Säkerställ att mapp finns */
		is_dir($this->mapp) or mkdir($this->mapp, 0770, true);

		/**
		 * Logga.
		 */
		$resultat = file_put_contents($this->textfil, $tipsrader);

		$kommentar = match (true) {
			$resultat > 0 => ": ✅ Sparade",
			default => ": ❌ Kunde inte spara"
		};

		$this->utdelning->spel->db->logg->logga(self::class .
			": $kommentar {$this->textfil}. ({$this->utdelning->spel->omgång})");
	}
}
