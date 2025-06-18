<?php

/**
 * Klass HistorisktUtfall.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

use PDO;
use Tips\Moduler\TT;

/**
 * Klass HistorisktUtfall.
 * Ger faktiskt utfall av tidigare matcher i samma oddsintervall.
 * Data hämtas från samtliga stryk- och europatipsomgångar.
 */
class HistorisktUtfall {
	/**
	 * @var array<int, float[]> $utfallshistorik
	 */
	public array $utfallshistorik;

	/**
	 * Initiera.
	 */
	public function __construct(public TT $tt) {
	}

	/**
	 * Beräkna historiskt utfall.
	 * Redovisas i matchtabell under historik.
	 */
	public function historiskt_utfall(): void {
		[$pmin, $pmax] = [0.93, 1.07]; // ± 7 % oddsintervall
		$resultat = [];

		/**
		 * Analysera odds från alla tipsomgångar.
		 */
		foreach ($this->tt->tt_odds as $match => $odds) {
			$query = '';
			for ($index = 1; $index <= 13; $index++) {
				$bas = 3 * ($index - 1);
				$pred = [$bas + 1, $bas + 2, $bas + 3]; // p1–p39, d.v.s. alla enskilda odds
				$query .= "SELECT `resultat$index` AS `res` FROM `odds` NATURAL JOIN `matcher`
					NATURAL JOIN `utdelning` WHERE `tipsrad_012` AND `resultat$index` AND
					$pmin * `p$pred[0]` < $odds[0] AND $pmax * `p$pred[0]` > $odds[0] AND
					$pmin * `p$pred[1]` < $odds[1] AND $pmax * `p$pred[1]` > $odds[1] AND
					$pmin * `p$pred[2]` < $odds[2] AND $pmax * `p$pred[2]` > $odds[2]";
				if ($index < MATCHANTAL) {
					$query .= " UNION ALL ";
				}
			}

			$sats = $this->tt->utdelning->spel->db->instans->prepare($query);
			$sats->execute();

			/**
			 * Addera varje utfall per match.
			 */
			$resultat[$match] = [0, 0, 0];
			foreach ($sats->fetchAll(PDO::FETCH_ASSOC) as $rad) {
				$res = explode('-', $rad['res']);
				/**
				 * $r1 > $r2: 2; $r1 === $r2: 1; $r1 < $r2: 0
				 * Observera: omkastad ordning
				 */
				$tecken = ($res[1] <=> $res[0]) + 1;
				$resultat[$match][$tecken]++;
			}
		}

		/**
		 * Sammanställ historik.
		 */
		$this->tt->utfallshistorik = [];
		foreach ($resultat as $index => $rad) {
			/**
			 * Nollvektor vid otillräcklig statistik.
			 */
			$this->tt->utfallshistorik[$index] = [0, 0, 0, 0];
			$summa = array_sum($rad);
			/**
			 * Format: andel 1X2 samt antal statistikposter.
			 * Andel i procent, avrundad till heltal.
			 */
			if ($summa > 0) {
				$this->tt->utfallshistorik[$index] = [
					round(100 * $rad[0] / $summa, 0),
					round(100 * $rad[1] / $summa, 0),
					round(100 * $rad[2] / $summa, 0),
					array_sum($rad)
				];
			}
		}

		$historik = array_map(fn ($utfall): string => implode(',', $utfall), $this->tt->utfallshistorik);

		$this->tt->db_preferenser->spara_preferens('topptips.historik', implode(',', $historik));
		$this->tt->utdelning->spel->db->logg->logga(self::class . ": ✅ Sparade TT-historik.");
	}
}
