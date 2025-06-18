<?php

/**
 * Klass Garderingstabellrad.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

use Tips\Moduler\TT;

/**
 * Klass Garderingstabellrad.
 */
class Garderingstabellrad {
	/**
	 * Initiera.
	 */
	public function __construct(protected TT $tt) {
	}

	/**
	 * Tabellrad för garderingar.
	 * @param string[] $k
	 */
	public function garderingstabellrad(
		int $i,
		int $sortering,
		string $sorteringsstil,
		array $k,
		string $td_rad
	): string {
		$matchindex = $i + 1;

		return <<< EOT
								<tr class="tt-match">
									<td class="match höger" id="tt_sortering$sortering">$matchindex</td>
									<td$sorteringsstil class="vänster">{$this->tt->hemmalag[$i]} – {$this->tt->bortalag[$i]}</td>
									<td$sorteringsstil class="center" id="tt_enkelrad$sortering">{$this->tt->enkelrad_1X2[$i]}</td>
$td_rad									<td><input tabindex="-1" data-sortering="$sortering" data-ruta="1" class="tipsruta tt_r grå									{$k[0]}" type="text" size="1" name="tt_reduktion[$i][0]" value="{$this->tt->reduktion[$i][0]}" maxlength="1"></td>
									<td><input tabindex="-1" data-sortering="$sortering" data-ruta="X" class="tipsruta tt_r grå{$k[1]}" type="text" size="1" name="tt_reduktion[$i][1]" value="{$this->tt->reduktion[$i][1]}" maxlength="1"></td>
									<td><input tabindex="-1" data-sortering="$sortering" data-ruta="2" class="tipsruta tt_r grå{$k[2]}" type="text" size="1" name="tt_reduktion[$i][2]" value="{$this->tt->reduktion[$i][2]}" maxlength="1"></td>
									<td class="tt-helgardering">🡄</td>
									<th class="tt-rensa-gardering">🞭</th>
									<td$sorteringsstil>$sortering</td>
								</tr>

EOT;
	}

	/**
	 * Beräkna fördelning av tecken i matchmatris.
	 * @return array<int, float[]>
	 */
	protected function generera_fördelning(): array {
		$tipsvektor = is_file($this->tt::TT_TEXTFIL) ? (array) file($this->tt::TT_TEXTFIL) : [];
		array_shift($tipsvektor); // ta bort huvud med information
		$antal_spelade_rader = max(count($tipsvektor), 1); // undvik nolldivision

		/**
		 * Uppdatera fördelning vid enskild punkt.
		 */
		$fördelning = $this->tt->tt_tom_oddsmatris;
		foreach ($tipsvektor as $rad) {
			$rad = symboler_till_siffror(str_replace([',', 'E', "\r\n",], TOM_STRÄNGVEKTOR, (string) $rad));
			foreach (str_split($rad) as $i => $tecken) {
				$fördelning[$i][$tecken]++;
			}
		}

		/**
		 * Omvandla till fraktioner.
		 */
		$fördelning = array_map(fn (array $odds): array =>
			array_map(fn (float $värde): float => fdiv($värde, $antal_spelade_rader), $odds), $fördelning);

		return $fördelning;
	}
}
