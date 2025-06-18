<?php

/**
 * Klass Fordelning.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\HG;

use Tips\Egenskaper\Eka;
use Tips\Moduler\HG\Konstanter;

/**
 * Klass Fordelning.
 * Beräkna fördelning av antal rätt över alla omgångar.
 * Exvs. 13 r uppnås i 4 % av fallen, men i teorin bara 0.5 %.
 * HG 11 prickar 11 r i 44 % av alla fall, motsvarande 221184 rader.
 * Totala reduktionen för HG 11 motsvarar därmed två säkra.
 */
class Fordelning extends Utdata {
	use Konstanter;
	use Eka;

	protected string $fördelning = '';

	/**
	 * Beräkna fördelning.
	 */
	protected function beräkna_fördelning(): void {
		$hg_vektor = $this->hg_vektor();
		$hg_summa = array_sum($hg_vektor);

		$ackumulerad_andel = 0;
		$delsträng = '';
		/**
		 * Iterera över utfall i alla omgångar.
		 */
		foreach ($hg_vektor as $hg_index => $antal) {
			$ackumulerad_andel += $andel = 100 * $antal / $hg_summa;
			[$antal_rader, $andel_teori] = self::HG_MATRIS[$hg_index];
			$faktor = $ackumulerad_andel / $andel_teori;

			/**
			 * Bilda tabellrad.
			 */
			$delsträng .= <<< EOT
							<tr class="höger">
								<td>$hg_index</td>
								<td>$antal_rader</td>
								<td{$this->eka(stil(1 - $andel / 100))}>{$this->eka(number_format($andel, 2))}</td>
								<td{$this->eka(stil(1 - $ackumulerad_andel / 100))}>{$this->eka(number_format($ackumulerad_andel, 2))}</td>
								<td>{$this->eka(number_format($andel_teori, 2))}</td>
								<td>{$this->eka(number_format($faktor, 2))}</td>
							</tr>

EOT;
		}

		/**
		 * Bilda tabell.
		 */
		$this->fördelning = <<< EOT
						<table id="hg-fördelning">
							<tr>
								<th>HG rätt</th>
								<th>Rader</th>
								<th>Andel %</th>
								<th>Ack. %</th>
								<th>Teori %</th>
								<th>Faktor</th>
							</tr>
$delsträng						</table>
EOT;

		/**
		 * Spara fördelning.
		 */
		$this->db_preferenser->spara_preferens('hg.fördelning', $this->fördelning);
	}
}
