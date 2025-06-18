<?php

/**
 * Klass Databas.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Parallellisering;

use PDO;
use Tips\Egenskaper\Eka;
use Tips\Egenskaper\Varden;
use Tips\Klasser\Spel;

/**
 * Klass Databas.
 */
class Databas {
	use Eka;
	use Varden;

	/**
	 * Initiera.
	 */
	public function __construct(public Spel $spel) {
		$this->hämta_värden($spel->db);
	}

	/**
	 * Vänta på databas.
	 * Sniffar temporär databas i intervaller så länge data finns.
	 * När ingen data finns kvar har bakgrundsprocessen upphört.
	 */
	public function vänta_på_databas(): void {
		$tid = hrtime()[0];
		while (hrtime()[0] - $tid < MAXTID) {
			$sats = $this->spel->db->temp->prepare("SELECT COUNT(`val`) FROM `parallellisering`");
			$sats->execute();
			if ($sats === false || $sats->fetchColumn() === $this->trådar) {
				break;
			}
			$sats->closeCursor();
			usleep(USÖMNTID);
		}

		$kommentar = match (hrtime()[0] - $tid < MAXTID) {
			true => ": ✅ Genererade rader.",
			false => ": ❌ Maxtid " . $this->eka(strval(MAXTID)) . " s överskriden."
		};
		$this->spel->db->logg->logga(self::class . $kommentar);
	}

	/**
	 * Populera databas.
	 * @param array<int, int> $vektorer
	 */
	public function populera_databas(string $data, array $vektorer): void {
		$sats = $this->spel->db->temp->prepare("REPLACE INTO `parallellisering` (`val`, `namn`) VALUES (:val, :namn)");
		$sats->bindValue(':val', $data, PDO::PARAM_STR);
		$sats->bindValue(':namn', "g{$vektorer[0]}{$vektorer[1]}{$vektorer[2]}{$vektorer[3]}", PDO::PARAM_STR);
		$sats->execute();
	}
}
