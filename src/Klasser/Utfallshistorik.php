<?php

/**
 * Klass Utfallshistorik.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

use PDO;

/**
 * Klass Utfallshistorik.
 * Historik över utfall i matcher inom oddsintervall.
 * Nyttjas i grid för favoriter, i kontrast till givna odds.
 * Data hämtas från samtliga stryk- och europatipsomgångar.
 */
final class Utfallshistorik {
	/**
	 * @var array<int, float[]> $utfallshistorik
	 */
	public array $utfallshistorik;
	/**
	 * @var int[] $ordnad_historik
	 */
	public array $ordnad_historik;

	/**
	 * Initiera.
	 */
	public function __construct(public Prediktioner $prediktioner) {
		$this->utfallshistorik();
	}

	/**
	 * Beäkna historik över utfall.
	 * Redovisas i favorittabell under historik.
	 */
	private function utfallshistorik(): void {
		/**
		 * Sammanställ historik.
		 */
		$this->utfallshistorik = [];
		foreach ($this->resultat() as $index => $rad) {
			/**
			 * Format: andel 1X2 samt antal statistikposter.
			 * Andel i procent, avrundad till heltal.
			 * Nollvektor vid otillräcklig statistik.
			 */
			$summa = array_sum($rad);
			$this->utfallshistorik[$index] = match (true) {
				$summa > 0 => [
					round(100 * $rad[0] / $summa, 0),
					round(100 * $rad[1] / $summa, 0),
					round(100 * $rad[2] / $summa, 0),
					array_sum($rad)
				],
				default => [0, 0, 0, 0]
			};
		}

		/**
		 * Historikfält.
		 */
		$historik = array_map(fn (array $utfall): array =>
			[$utfall[0], $utfall[1], $utfall[2]], $this->utfallshistorik);

		/**
		 * Sortera.
		 */
		$sortering = array_map(fn (array $sort): float => (float) ne_max($sort), $historik);
		arsort($sortering);
		$this->ordnad_historik = array_flip(array_keys($sortering));
	}

	/**
	 * Hämta oddsdata från databas.
	 * @return array<int, int[]>
	 */
	private function resultat(): array {
		[$pmin, $pmax] = [0.93, 1.07]; // ± 7 % oddsintervall
		$resultat = [];

		/**
		 * Analysera odds från alla tipsomgångar.
		 */
		foreach ($this->prediktioner->prediktioner as $match => $odds) {
			$query = '';

			/**
			 * Iterera över matchnummer
			 */
			for ($index = 1; $index <= MATCHANTAL; $index++) {
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

			$sats = $this->prediktioner->spel->db->instans->prepare($query);
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

		return $resultat;
	}
}
