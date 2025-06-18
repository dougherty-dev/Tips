<?php

/**
 * Klass Spelade.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

use PDO;
use Tips\Klasser\Spelade\Radera;

/**
 * Klass Spelade.
 */
final class Spelade extends Radera {
	/**
	 * Omdefiniera konstruktorn.
	 */
	public function __construct(protected Utdelning $utdelning, protected Matcher $matcher) {
		parent::__construct($utdelning, $matcher);
		$this->hämta_genererade_rader();
	}

	/**
	 * Hämta genererade rader.
	 */
	private function hämta_genererade_rader(): void {
		$sats = $this->utdelning->spel->db->instans->prepare("SELECT `tipsrader`, `genererade`, `valda`, `kommentar`
			FROM `spelade` WHERE `omgång`=:omgang AND `speltyp`=:speltyp AND `sekvens`=:sekvens LIMIT 1");
		$sats->bindValue(':omgang', $this->utdelning->spel->omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $this->utdelning->spel->speltyp->value, PDO::PARAM_INT);
		$sats->bindValue(':sekvens', $this->utdelning->spel->sekvens, PDO::PARAM_INT);
		$sats->bindColumn('tipsrader', $this->tipsrader, PDO::PARAM_STR);
		$sats->bindColumn('genererade', $this->antal_genererade, PDO::PARAM_INT);
		$sats->bindColumn('valda', $this->antal_utvalda_rader, PDO::PARAM_INT);
		$sats->bindColumn('kommentar', $this->kommentar, PDO::PARAM_STR);
		$sats->execute();
		$sats->fetch(PDO::FETCH_OBJ) and $this->tipsvektor = bas36till3(explode(',', $this->tipsrader));
		$this->spelad = boolval(count($this->tipsvektor));
	}
}
