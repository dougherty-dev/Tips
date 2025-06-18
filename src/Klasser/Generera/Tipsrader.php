<?php

/**
 * Klass Tipsrader.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Generera;

use Tips\Egenskaper\Varden;
use Tips\Klasser\Tips;

/**
 * Klass Tipsrader.
 */
class Tipsrader {
	protected float $tid;
	protected int $antal_genererade = 0;

	public function __construct(protected Tips $tips) {
	}

	/**
	 * Generera rader.
	 */
	protected function generera_tipsrader(): void {
		$this->tid = hrtime(true);
		$this->tips->odds->spel->db->temp->exec("DELETE FROM `genererat`");
		$this->rensa_genererade_bildfiler();
		$this->tips->parallellisering->parallellisera();
		$this->tips->spelade->tipsvektor = bas36till3($this->tips->parallellisering->behandla_parallellisering());
		$this->antal_genererade = count($this->tips->spelade->tipsvektor);
	}

	/**
	 * Rensa temporära filer.
	 */
	public function rensa_genererade_bildfiler(): void {
		foreach ((array) glob(GRAF . GENERERADE . "/*.png") as $fil) {
			$kommentar = match (is_string($fil) && file_exists($fil) && unlink($fil)) {
				true => ": ✅ Raderade fil",
				false => ": ❌ Kunde inte radera fil"
			};

			$this->tips->spel->db->logg->logga(self::class . "$kommentar $fil.");
		}
	}
}
