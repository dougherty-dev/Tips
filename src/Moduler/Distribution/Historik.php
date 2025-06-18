<?php

/**
 * Klass Historik.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Distribution;

use PDO;
use Tips\Egenskaper\Eka;

/**
 * Klass Historik.
 * Förekomst av procentintervall för några godtyckliga segment.
 * Det visar sig att 2/3 av alla omgångar faller inom 10 %.
 */
class Historik extends Radera {
	use Eka;
	use Konstanter;

	public string $historik = '';

	/**
	 * Historik för distribution.
	 */
	public function historik(): void {
		/**
		 * Tabellhuvud.
		 */
		$this->historik = <<< EOT
						<table>
							<tr class="match">
								<th>Andel rader</th><th>Förekomst</th><th>Ackumulerad</th><th>Antal rader</th>
							</tr>

EOT;

		/**
		 * Sammanställ procentandelar.
		 */
		$procentandelar = $this->procentandelar();
		$antal = count($procentandelar);
		$ackumlerad_andel = 0.0;

		/**
		 * Segment definieras godtyckligt som intervall över några procentsatser.
		 * 25 % av alla utfall hamnar inom 1 % rader i denna distribution.
		 * 35 % inom 2 %, 50 % inom 4 % och 65 % inom 10 %.
		 * Det sistnämnda är 90 % reduktion, motsvarande två säkra matcher.
		 */
		foreach ($this->segment($procentandelar) as $procentsats => $antal_per_segment) {
			$procent = (float) $procentsats;
			$andel = count($antal_per_segment) * 100 / $antal;
			$ackumlerad_andel += $andel;

			/**
			 * Bygg tabellrader.
			 */
			$this->historik .= <<< EOT
							<tr class="höger">
								<td>≤ {$this->eka(number_format($procent, 2))} %</td>
								<td>{$this->eka(number_format($andel, 2, '.', ' '))} %</td>
								<td>{$this->eka(number_format($ackumlerad_andel, 2))} %</td>
								<td>{$this->eka(number_format($procent * MATCHRYMD / 100, 0, '.', ' '))}</td>
							</tr>

EOT;
		}

		/**
		 * Tabulera och spara.
		 */
		$this->historik .= t(6, "</table>");
		$this->db_preferenser->spara_preferens('distribution.historik', $this->historik);
	}

	/**
	 * Hämta segment.
	 * @param float[] $procentandelar
	 * @return array<int|string, float[]>
	 */
	private function segment(array $procentandelar): array {
		/**
		 * Iterera över segment och addera förekomst.
		 */
		$segment = [];
		foreach ($procentandelar as $procentandel) {
			foreach (self::DISTRIBUTION_GRÄNSER as $gräns) {
				if ($procentandel <= $gräns) {
					$segment[strval($gräns)][] = $procentandel;
					break;
				}
			}
		}

		return $segment;
	}

	/**
	 * Hämta procentandelar.
	 * @return float[]
	 */
	private function procentandelar(): array {
		/**
		 * Hämta procentandelar.
		 */
		$sats = $this->odds->spel->db->instans->prepare("SELECT `procentandel`
			FROM `distribution` WHERE `oddssumma`>0");
		$sats->execute();
		$procentandelar = $sats->fetchAll(PDO::FETCH_COLUMN, 0);
		sort($procentandelar);

		/**
		 * Skicka hem.
		 */
		return $procentandelar;
	}
}
