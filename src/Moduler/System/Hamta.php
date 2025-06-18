<?php

/**
 * Klass Hamta.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\System;

use PDO;

/**
 * Klass Hamta.
 * Hämta garderingar från databas och packa upp.
 */
class Hamta extends Preferenser {
	/**
	 * @var array<int, string[]> $reduktion
	 */
	public array $reduktion;

	/**
	 * Hämta garderingar från databas
	 */
	protected function hämta_system(): void {
		$garderingar = '';
		$andel_garderingar = '';
		$sats = $this->odds->spel->db->instans->prepare('SELECT `garderingar`, `andel_garderingar`
			FROM `system` WHERE `omgång`=:omgang AND `speltyp`=:speltyp AND `sekvens`=:sekvens LIMIT 1');
		$sats->bindValue(':omgang', $this->odds->spel->omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $this->odds->spel->speltyp->value, PDO::PARAM_INT);
		$sats->bindValue(':sekvens', $this->odds->spel->sekvens, PDO::PARAM_INT);
		$sats->bindColumn('garderingar', $garderingar, PDO::PARAM_STR);
		$sats->bindColumn('andel_garderingar', $andel_garderingar, PDO::PARAM_STR);
		$sats->execute();

		/**
		 * Packa upp garderingar.
		 */
		if ($sats->fetch()) {
			if ($garderingar) {
				$this->garderingar = array_chunk(array_chunk(explode(',', $garderingar), 3), MATCHANTAL);
			}

			if ($andel_garderingar) {
				$this->andel_garderingar = array_map('intval', explode(',', $andel_garderingar));
			}
		}

		/**
		 * Extrahera halvgarderingar.
		 */
		foreach ($this->garderingar as $index => $gardering) {
			foreach ($gardering as $tecken) {
				/**
				 * Halvgardering = en ruta tom.
				 */
				$har_hg = array_any($tecken, function (string $value) {
					return $value !== '';
				});

				if ($har_hg) {
					$this->antal_garderingar[$index]++;
				}
			}

			$this->antal_garderingar[$index] = min($this->antal_garderingar[$index], $this->antal_garderingar[$index]);
		}

		$reduktion = $this->db_preferenser->hämta_preferens("system.reduktion");

		/**
		 * Finns reduktion i preferenser är allt väl.
		 * Annars sparas en tom datastruktur.
		 */
		match ($reduktion !== '') {
			true => $this->reduktion = array_chunk(explode(',', $reduktion), 3),
			false => $this->db_preferenser->spara_preferens(
				"system.reduktion",
				implode(',', array_merge([], ...array_fill(0, MATCHANTAL, TOM_STRÄNGVEKTOR)))
			)
		};
	}
}
