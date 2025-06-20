<?php

/**
 * Klass GridMatcher.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Omgang;

use Tips\Klasser\Tips;
use Tips\Egenskaper\Eka;

define("ODDSSTIL", array_fill(0, 3, ' class="oddskolumn odefinierad"'));
define("STRECKSTIL", array_fill(0, 3, ' class="streckkolumn odefinierad"'));

/**
 * Klass GridMatcher.
 * Visa matchtabell med lag, odds, streck med mera.
	 * Visar:
	 * - matchnummer
	 * - fördelning av spelade tecken per match
	 * - matchstatus
	 * - match (lag)
	 * - resultat
	 * - odds
	 * - odddsannolikheter
	 * - streck
	 * - streckodds
 */
final class GridMatcher extends GridMatcherFordelning {
	use Eka;

	/**
	 * Visa matcher.
	 */
	public function visa(): string {
		/**
		 * Initiera.
		 */
		$rätt = ['odds' => 0, 'streck' => 0];

		/**
		 * Definiera tabindex för smidig manuell inmatning.
		 * Tabulator vandrar över kolumner med matcher, odds och streck.
		 */
		$tix = ['m' => range(100, 99 + MATCHANTAL),
				'o' => range(200, 199 + 3 * MATCHANTAL),
				's' => range(300, 299 + 3 * MATCHANTAL)];

		/**
		 * Nollställ stilar.
		 */
		$fördelning = TOM_ODDSMATRIS;

		/**
		 * Beräkna fördelning av tecken i respektive match.
		 */
		$this->fördelning($fördelning);

		/**
		 * Gråskala för fördelning.
		 * Vitt motsvarar 0, svart 1.
		 */
		$teckenfördelning = array_map(fn (array $odds): array =>
			array_map(fn (float $värde): string => stil($värde), $odds), $fördelning);

		/**
		 * Formatera sannolikheter med två decimaler.
		 * Fördelning av spelade tecken, odds samt streck.
		 */
		$formaterad_dist = formatera_sannolikheter($fördelning);
		$odds = formatera_sannolikheter($this->tips->odds->prediktioner);
		$streckodds = formatera_sannolikheter(sannolikheter_till_odds($this->tips->streck->sannolikheter));

		/**
		 * Iterera över matcher.
		 */
		$grid_matcher = '';
		foreach (array_keys($this->tips->odds->sannolikheter) as $index) {
			/**
			 * Nollställ stil.
			 */
			[$oddsstil, $streckstil] = [ODDSSTIL, STRECKSTIL];
			$this->stila_sannolikheter($index, $oddsstil, $streckstil, $rätt);

			/**
			 * Gulmarkera om odds saknas för match.
			 * Streckodds kan överföras till odds för att kunna generera rader.
			 */
			$odds_finns = $this->tips->odds->prediktioner[$index][0] == 0 ? ' förlust' : '';

			/**
			 * Bygg tabellrad.
			 * 1: Matchnummer
			 * 2–4: Fördelning av tecken för spelade matcher
			 * 5–7: Matchstatus, lag, favoritskap
			 * 8–9: Resultat, tipstecken
			 * 10–12: Odds
			 * 13–15: Decimalodds
			 * 16–18: Streck
			 * 19–21: Sannolikheter
			 * 22–24: Streckodds
			 * 25: JS streckodds => odds då odds saknas.
			 */
			$grid_matcher .= $this->eka_tabell(
				$index,
				$odds_finns,
				$teckenfördelning,
				$formaterad_dist,
				$odds,
				$streckodds,
				$oddsstil,
				$streckstil,
				$tix
			);
		}

		return $this->matchtabell($rätt, $grid_matcher);
	}

	/**
	 * Eka matcher
	 * @param array<int, string[]> $teckenfördelning
	 * @param array<int, string[]> $formaterad_dist
	 * @param array<int, string[]> $odds
	 * @param array<int, string[]> $streckodds
	 * @param string[] $oddsstil
	 * @param string[] $streckstil
	 * @param array<string, int[]> $tix
	 */
	private function eka_tabell(
		int $index,
		string $odds_finns,
		array $teckenfördelning,
		array $formaterad_dist,
		array $odds,
		array $streckodds,
		array $oddsstil,
		array $streckstil,
		array $tix
	): string {
		/**
		 * Bygg tabellrad.
		 * 1: Matchnummer
		 * 2–4: Fördelning av tecken för spelade matcher
		 * 5–7: Matchstatus, lag, favoritskap
		 * 8–9: Resultat, tipstecken
		 * 10–12: Odds
		 * 13–15: Decimalodds
		 * 16–18: Streck
		 * 19–21: Sannolikheter
		 * 22–24: Streckodds
		 * 25: JS streckodds => odds då odds saknas.
		 */
		return <<< EOT
								<tr>
									<td class="match höger">{$this->eka(strval($index + 1))}</td>
									<td{$teckenfördelning[$index][0]}>{$formaterad_dist[$index][0]}</td>
									<td{$teckenfördelning[$index][1]}>{$formaterad_dist[$index][1]}</td>
									<td{$teckenfördelning[$index][2]}>{$formaterad_dist[$index][2]}</td>
									<td><input type="hidden" name="matchstatus[]" value="{$this->tips->matcher->matchstatus[$index]}">{$this->eka($this->tips->matcher->matchstatus[$index] ? '✔️' : '✖️')}</td>
									<td{$this->tips->odds->sorteringsstil[$index]} class="mindre"><input{$this->tips->odds->sorteringsstil[$index]} class="vänster" tabindex="{$tix['m'][$index]}" type="text" name="lag[]" size="25" value="{$this->tips->matcher->match[$index]}"></td>
									<td{$this->tips->odds->sorteringsstil[$index]}>{$this->tips->odds->sortering[$index]}</td>
									<td class="mindre"><input type="text" name="resultat[]" size="3" value="{$this->tips->matcher->resultat[$index]}"></td>
									<td class="vinstrad">{$this->eka($this->tips->utdelning->har_tipsrad ? $this->tips->utdelning->tipsrad[$index] : '')}</td>
									<td class="input$odds_finns o0"><input{$oddsstil[0]} tabindex="{$tix['o'][3 * $index]}" type="text" name="odds[$index][]" size="2" maxlength="5" value="{$odds[$index][0]}"></td>
									<td class="input$odds_finns o1"><input{$oddsstil[1]} tabindex="{$tix['o'][3 * $index + 1]}" type="text" name="odds[$index][]" size="2" maxlength="5" value="{$odds[$index][1]}"></td>
									<td class="input$odds_finns o2"><input{$oddsstil[2]} tabindex="{$tix['o'][3 * $index + 2]}" type="text" name="odds[$index][]" size="2" maxlength="5" value="{$odds[$index][2]}"></td>
									{$this->tips->odds->stil_sannolikheter[$index][0]}{$this->tips->odds->stil_sannolikheter[$index][1]}{$this->tips->odds->stil_sannolikheter[$index][2]}
									<td class="input"><input{$streckstil[0]} tabindex="{$tix['s'][3 * $index]}" type="text" name="streck[$index][]" size="2" maxlength="2" value="{$this->tips->streck->prediktioner[$index][0]}"></td>
									<td class="input"><input{$streckstil[1]} tabindex="{$tix['s'][3 * $index + 1]}" type="text" name="streck[$index][]" size="2" maxlength="2" value="{$this->tips->streck->prediktioner[$index][1]}"></td>
									<td class="input"><input{$streckstil[2]} tabindex="{$tix['s'][3 * $index + 2]}" type="text" name="streck[$index][]" size="2" maxlength="2" value="{$this->tips->streck->prediktioner[$index][2]}"></td>
									{$this->tips->streck->stil_sannolikheter[$index][0]}{$this->tips->streck->stil_sannolikheter[$index][1]}{$this->tips->streck->stil_sannolikheter[$index][2]}
									<td class="s0 oddskolumn">{$streckodds[$index][0]}</td>
									<td class="s1 oddskolumn">{$streckodds[$index][1]}</td>
									<td class="s2 oddskolumn">{$streckodds[$index][2]}</td>
									<td class="återvinn">{$this->eka($odds[$index][0] === '0.00' ? '♻️' : '')}</td>
								</tr>

EOT;
	}
}
