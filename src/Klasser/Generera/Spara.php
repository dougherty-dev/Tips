<?php

/**
 * Klass Spara.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Generera;

use PDO;
use Tips\Egenskaper\Varden;

/**
 * Klass Spara.
 */
class Spara extends Plotta {
	use Varden;

	/**
	 * Spara tipsrader till databas.
	 * Raderna sparas i temporär databas innan de godkänns som spel.
	 */
	protected function spara_genererade_tipsrader(): void {
		$sats = $this->tips->spel->db->temp->prepare("REPLACE INTO `genererat`
			(`omgång`, `speltyp`, `sekvens`, `tipsrader`, `datum`, `genererade`, `valda`, `kommentar`)
			VALUES (:omgang, :speltyp, :sekvens, :tipsrader, :datum, :genererade, :valda, :kommentar)");
		$sats->bindValue(':omgang', $this->tips->spel->omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $this->tips->spel->speltyp->value, PDO::PARAM_INT);
		$sats->bindValue(':sekvens', $this->tips->spel->sekvens, PDO::PARAM_INT);
		$sats->bindValue(':tipsrader', implode(',', bas3till36($this->tips->spelade->tipsvektor)), PDO::PARAM_STR);
		$sats->bindValue(':datum', date("Y-m-d H:i:s"), PDO::PARAM_STR);
		$sats->bindValue(':genererade', $this->antal_genererade, PDO::PARAM_INT);
		$sats->bindValue(':valda', $this->antal_utvalda_rader, PDO::PARAM_INT);
		$sats->bindValue(':kommentar', $this->kommentar(), PDO::PARAM_STR);

		$kommentar = match ($sats->execute()) {
			true => ": ✅ Sparade tipsrader.",
			false => ": ❌ Kunde inte spara tipsrader."
		};

		$this->tips->spel->db->logg->logga(self::class . "$kommentar ({$this->tips->spel->omgång})");
	}

	/**
	 * Hämta kommentar från modul.
	 * Informationen visas i ruta för strategi.
	 * Visar antal rader, nyttjat vinstintervall, samt aktiva moduler med parametrar.
	 */
	private function kommentar(): string {
		$kommentar = ["r={$this->max_rader}, [{$this->u13_min}–{$this->u13_max}]"];
		foreach ($this->tips->moduler->m_moduler as $modul) {
			if (method_exists($modul, 'kommentar')) {
				$kommentar[] = $modul->kommentar();
			}
		}

		return implode("\n", $kommentar);
	}
}
