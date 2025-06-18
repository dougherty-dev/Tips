<?php

/**
 * Klass Andelar.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Andel;

use PDO;

/**
 * Klass Andelar.
 */
class Andelar extends Prova {
	/**
	 * Beräkna andelar av tecken i en rad.
	 * Vanligast fördelningar av 1X2 är 6-3-4, 6-4-3, 6-2-5 och 7-3-3.
	 * Ovanligast är skeva fördelningar.
	 * Tio vanligaste fördelningarna motsvarar 50 % av alla rader.
	 */
	protected function andelar(): string {
		$andelsdata = []; // håller historiskt antal av respektive andel

		/**
		 * Generera alla möjliga kombinationer av antal 1X2.
		 * Summan av antal 1X2 är alltid 13.
		 */
		foreach ($rymd = range(0, MATCHANTAL) as $rymd0) {
			foreach ($rymd as $rymd1) {
				$rymd2 = MATCHANTAL - $rymd0 - $rymd1; // entydigt bestämd
				$andelsdata["$rymd0-$rymd1-$rymd2"] = 0;
			}
		}

		/**
		 * Hämta tipsrad för alla omgångar.
		 * Ta fram fördelning över teckenandelar.
		 */
		$antal_tipsrader =  0; // totalt antal rader
		$sats = $this->utdelning->spel->db->instans->prepare(
			"SELECT `omgång`, `u12`, `tipsrad_012` FROM `utdelning`
			WHERE `tipsrad_012` AND `u12` ORDER BY `u12` DESC"
		);
		$sats->execute();
		foreach ($sats->fetchAll(PDO::FETCH_ASSOC) as $rad) {
			$antal_tipsrader++;
			$fördelning = $this->teckenfördelning($rad['tipsrad_012']);
			$andelsdata["{$fördelning[0]}-{$fördelning[1]}-{$fördelning[2]}"]++;
		}

		arsort($andelsdata);
		return $this->beräkna_andelar($andelsdata, $antal_tipsrader);
	}

	/**
	 * Sammanfatta andelar.
	 * @param array<string, int> $andelsdata
	 */
	private function beräkna_andelar(array $andelsdata, int $antal_tipsrader): string {
		$text = '';
		$kumlativ_andel = '';
		$antal_valda =  0;

		foreach ($andelsdata as $index => $data) {
			if ($data >= 1) { // ta bara med verkliga utfall
				$antal_valda += $data;
				$kumlativ_andel = number_format(100 * $antal_valda / $antal_tipsrader, 2);

				$spred = array_map('intval', explode('-', $index));
				$stil = $this->pröva_andelar($spred) ? 'class="vinst"' : 'class="storförlust"';

				$text .= t(5, "<span $stil>$index</span> => $data | $kumlativ_andel %<br>");
			}
		}

		return $text . t(5, "<p>Andelar:<br>$antal_valda / $antal_tipsrader = $kumlativ_andel %</p>");
	}
}
