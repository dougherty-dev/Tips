<?php

/**
 * Klass HamtaData.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

use PDO;

/**
 * Klass HamtaData.
 * Hämta data för topptispet från databas.
 * För närvarande finns bara momentan match.
 */
class HamtaData extends Preferenser {
	public int $omgång = 0;
	public int $omsättning = 0;
	public int $överskjutande = 0; // ifall ingen vinnare finns
	public int $extrapengar = 0; // 250000 extra pott emellanåt
	public int $antal_rader = 100; // standardvärde för antal spelade rader
	public string $spelstopp = '';

	/**
	 * Hämta data.
	 */
	protected function hämta_data(): void {
		$this->antal_rader = (int) $this->db_preferenser->hämta_preferens('topptips.antal_rader');

		$sats = $this->utdelning->spel->db->instans->prepare("SELECT * FROM `TT` LIMIT 1");
		$sats->execute();
		foreach ($sats->fetchAll(PDO::FETCH_ASSOC) as $rad) {
			$this->omgång = (int) $rad['omgång'];
			$this->omsättning = (int) $rad['omsättning'];
			$this->spelstopp = $rad['spelstopp'];
			$this->överskjutande = $rad['överskjutande'];
			$this->extrapengar = $rad['extrapengar'];
			$this->hemmalag = explode('|', $rad['hemmalag']);
			$this->bortalag = explode('|', $rad['bortalag']);

			/**
			 * Packa upp strängar till oddsmatriser.
			 */
			$tt_odds = array_chunk(explode(',', $rad['odds']), 3);
			foreach ($tt_odds as $i => $m) {
				$this->tt_odds[$i] = array_map('floatval', $m);
			}

			$tt_streck = array_chunk(explode(',', $rad['streck']), 3);
			foreach ($tt_streck as $i => $s) {
				$this->tt_streck[$i] = array_map('intval', $s);
			}
		}
	}
}
