<?php

/**
 * Klass Spara.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

use PDO;
use Tips\Moduler\TT;

/**
 * Klass Spara.
 * Spara aktuell omgång för Topptipset i databas.
 */
class Spara {
	public function __construct(private TT $tt) {
	}

	/**
	 * Spara topptips.
	 * För närvarande sparas bara aktuell omgång.
	 */
	protected function spara_data(): void {
		$this->tt->utdelning->spel->db->instans->exec("DELETE FROM `TT`");
		$sats = $this->tt->utdelning->spel->db->instans->prepare("REPLACE INTO `TT`
			(`omgång`, `omsättning`, `spelstopp`, `överskjutande`, `extrapengar`,
			`hemmalag`, `bortalag`, `odds`, `streck`) VALUES
			(:omgang, :omsattning, :spelstopp, :overskjutande, :extrapengar,
			:hemmalag, :bortalag, :odds, :streck)");
		$sats->bindValue(':omgang', $this->tt->omgång, PDO::PARAM_INT);
		$sats->bindValue(':omsattning', $this->tt->omsättning, PDO::PARAM_INT);
		$sats->bindValue(':spelstopp', $this->tt->spelstopp, PDO::PARAM_STR);
		$sats->bindValue(':overskjutande', $this->tt->överskjutande, PDO::PARAM_STR);
		$sats->bindValue(':extrapengar', $this->tt->extrapengar, PDO::PARAM_STR);
		$sats->bindValue(':hemmalag', implode('|', $this->tt->hemmalag), PDO::PARAM_STR);
		$sats->bindValue(':bortalag', implode('|', $this->tt->bortalag), PDO::PARAM_STR);
		$sats->bindValue(':odds', implode(',', array_merge(...$this->tt->tt_odds)), PDO::PARAM_STR);
		$sats->bindValue(':streck', implode(',', array_merge(...$this->tt->tt_streck)), PDO::PARAM_STR);

		$kommentar = match ($sats->execute()) {
			true => ": ✅ Sparade TT-data.",
			false => ": ❌ Kunde inte spara TT-data."
		};

		$this->tt->utdelning->spel->db->logg->logga(self::class . "$kommentar ({$this->tt->omgång})");
		$this->tt->historik->historiskt_utfall();
	}
}
