<?php

/**
 * Klass GridResultatTipsresultat.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Omgang;

/**
 * Klass GridResultatTipsresultat.
 */
class GridResultatTipsresultat extends GridResultatVinstrader {
	/**
	 * Visa tipsresultat.
	 * Antal rätt per vinstkategori, förväntat resultat med mera.
	 */
	protected function tipsresultat(): string {
		if (!$this->spelad) {
			return '';
		}

		/**
		 * Förväntat resultat, slumpmässig fördelning.
		 * Exempel, 100 spelade rader:
		 * 5 r: 21 %
		 * 6 r: 14 %
		 * 7 r: 7 %
		 * 8 r: 2.6 %
		 * 9 r: 0.7 %
		 * 10 r: 0.14 %
		 * 11 r: 0.02 %
		 */
		$förväntat_antal = array_map(
			fn (float $n): float => $n / (3 ** MATCHANTAL / $this->antal_rader),
			array_reverse(UTFALL_PER_HALVGARDERINGAR[MATCHANTAL])
		);

		$tipsresultat = $this->tabellhuvud();

		for ($i = MATCHANTAL; $i >= 0; $i--) {
			$förväntade = number_format($förväntat_antal[$i], 4);
			$faktor = number_format($this->rättvektor[$i] / $förväntat_antal[$i], 2, '.', '');
			$stil = ($i >= 10 && $this->rättvektor[$i] > 0) ? " class=\"vinst$i\"" : '';

			/**
			 * Tabellrad.
			 */
			$tipsresultat .= <<< EOT
							<tr class="höger">
								<td>$i</td>
								<td$stil>{$this->rättvektor[$i]}</td>
								<td>$förväntade</td>
								<td>$faktor</td>
							</tr>

EOT;
		}

		return <<< EOT
$tipsresultat						</table>

EOT;
	}

	/**
	 * Tabellhuvud.
	 * Dela upp rutiner.
	 */
	private function tabellhuvud(): string {
		$vinst_netto = $this->vinst - $this->antal_rader;
		$stil = $vinst_netto > 0 ? ['<span class="nettovinst">', '</span>'] : ['', ''];

		/**
		 * Skicka tillbaka.
		 */
		return <<< EOT
						<p>Vinst: {$stil[0]}$vinst_netto{$stil[1]} ({$this->vinst}) kr</p>
						<table class="ram">
							<tr class="match">
								<th>Rätt</th>
								<th>Antal</th>
								<th>E[r|{$this->antal_rader}]</th>
								<th>Faktor</th>
							</tr>

EOT;
	}
}
