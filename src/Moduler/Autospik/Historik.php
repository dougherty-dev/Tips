<?php

/**
 * Klass Historik.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Autospik;

use Tips\Egenskaper\Eka;
use Tips\Moduler\Autospik;

/**
 * Klass Historik.
 * Bygger historik över utfall av Autospik.
 * Enskilda respektive kumulativa andelar i procent.
 */
class Historik extends GridGarderingar {
	use Eka;

	protected string $historik = '';

	/**
	 * Historik för autospik.
	 * Rankad match 1 går in i 75 % av fallen.
	 * Matcher 1 och 2 i 52 %.
	 * 1, 2, 3 i 35 %.
	 */
	protected function autospik_historik(): void {
		$this->historik = <<< EOT
						<table class="höger">
							<tr class="match">
								<th>#</th>
								<th>Antal</th>
								<th>Andel %</th>
								<th>Kumulativ %</th>
							</tr>

EOT;

		/**
		 * Hämta prediktioner och tipsrader för samtliga kompletta omgångar.
		 */
		$historik = [];
		$prediktioner = $this->odds->prediktionsdata('odds');
		$tipsdata = $this->odds->tipsdata();

		/**
		 * Iterera över alla tipsrader.
		 */
		foreach ($tipsdata as $omgång => $tipsrad_012) {
			if (!isset($prediktioner[$omgång])) {
				continue;
			}

			/**
			 * Hämta summering.
			 */
			$summa = $this->summera($tipsrad_012, $prediktioner[$omgång]);

			/**
			 * Öka (eller sätt) summa för antal matcher som går in.
			 */
			$historik[$summa] = isset($historik[$summa]) ? $historik[$summa] + 1 : 1;
		}

		/**
		 * Reversera ordningen.
		 */
		krsort($historik);
		$this->bygg_historik($historik);

		/**
		 * Spara historik, uppdateras då omgång sparas generellt.
		 */
		$this->historik .= t(6, "</table>");
		$this->db_preferenser->spara_preferens('autospik.historik', $this->historik);
	}

	/**
	 * Summera.
	 * Räknar antalet konsekutiva spikar i favoritordning.
	 * @param array<int, float[]> $prediktioner
	 */
	private function summera(string $tipsrad_012, array $prediktioner): int {
		/**
		 * Sortera sannolikheter.
		 */
		$sannolikheter = odds_till_sannolikheter($prediktioner);
		$sortering = array_keys(ordna_sannolikheter($sannolikheter));
		$summa = 0;

		/**
		 * Iterera över ordnade sannolikheter.
		 * Så länge matcher går in ökas summan, annars bryts exekveringen.
		 */
		foreach ($sortering as $index) {
			if (
				array_search(
					ne_max($sannolikheter[$index]),
					$sannolikheter[$index],
					true
				) != $tipsrad_012[$index]
			) {
				break;
			}

			$summa++;
		}

		/**
		 * Skicka tillbaka.
		 */
		return $summa;
	}

	/**
	 * Bygg historik från data.
	 * @param int[] $historik
	 */
	private function bygg_historik(array $historik): void {
		/**
		 * Summan av alla utfall.
		 */
		$hsumma = array_sum($historik);
		$andel_kumulativ = 0;

		/**
		 * Reverserad ordning.
		 * Ex. 11 matcher i följd går in 1 gång, andel 0.1 %, kumulativ 0.10 %
		 * 10 matcher 1 gång, andel 0.1 %, kumulativ 0.19 %
		 * …
		 * 5 matcher 59 gånger. andel 5.72 %, kumulativ 12.61 %
		 * …
		 * 2 matcher 177 gånger, andel 17.17 %, kumulativ 74.49 %
		 */
		foreach ($historik as $autospikar => $antal) {
			/**
			 * Andelar och stil.
			 */
			$andel = 100 * $antal / $hsumma;
			$andel_kumulativ += $andel;
			$ackstil = stil($andel_kumulativ / 100); // gråskala 0–100 = vit till svart

			/**
			 * Eka ut HTML.
			 */
			$this->historik .= <<< EOT
							<tr>
								<td>$autospikar</td>
								<td>$antal</td>
								<td>{$this->eka((string) round($andel, 2))}</td>
								<td$ackstil>{$this->eka((string) number_format($andel_kumulativ, 2))}</td>
							</tr>

EOT;
		}
	}
}
