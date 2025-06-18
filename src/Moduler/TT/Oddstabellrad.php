<?php

/**
 * Klass Oddstabellrad.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

use Tips\Moduler\TT;

/**
 * Klass Oddstabellrad.
 * För topptipset.
 */
final class Oddstabellrad {
	/**
	 * Initiera.
	 */
	public function __construct(protected TT $tt) {
	}

	/**
	 * Tabellrad för prediktionsdata.
	 * @param string[] $teckenfördelning
	 * @param string[] $formaterad_dist
	 * @param int[] $tix
	 * @param string[] $odds
	 * @param string[] $oddsstil
	 * @param string[] $streckstil
	 */
	public function oddstabellrad(
		int $index,
		string $sorteringsstil,
		array $teckenfördelning,
		array $formaterad_dist,
		string $odds_finns,
		array $tix,
		array $odds,
		array $oddsstil,
		array $streckstil
	): string {
		$matchindex = $index + 1;

		/**
		 * SKicka till TT\Matchtabeller.
		 */
		return <<< EOT
								<tr>
									<td class="match höger">$matchindex</td>
									<td{$teckenfördelning[0]}>{$formaterad_dist[0]}</td>
									<td{$teckenfördelning[1]}>{$formaterad_dist[1]}</td>
									<td{$teckenfördelning[2]}>{$formaterad_dist[2]}</td>
									<td$sorteringsstil class="vänster">{$this->tt->hemmalag[$index]} – {$this->tt->bortalag[$index]}</td>
									<td class="input$odds_finns"><input class="oddskolumn" tabindex="{$tix[3 * $index]}" type="text" name="tt_odds[$index][]" size="2" maxlength="5" value="{$odds[0]}"></td>
									<td class="input$odds_finns"><input class="oddskolumn" tabindex="{$tix[3 * $index + 1]}" type="text" name="tt_odds[$index][]" size="2" maxlength="5" value="{$odds[1]}"></td>
									<td class="input$odds_finns"><input class="oddskolumn" tabindex="{$tix[3 * $index + 2]}" type="text" name="tt_odds[$index][]" size="2" maxlength="5" value="{$odds[2]}"></td>
									{$oddsstil[0]}
									{$oddsstil[1]}
									{$oddsstil[2]}
									<td class="höger">{$this->tt->tt_streck[$index][0]}</td>
									<td class="höger">{$this->tt->tt_streck[$index][1]}</td>
									<td class="höger">{$this->tt->tt_streck[$index][2]}</td>
									{$streckstil[0]}
									{$streckstil[1]}
									{$streckstil[2]}
									<td class="höger">{$this->tt->utfallshistorik[$index][0]}</td>
									<td class="höger">{$this->tt->utfallshistorik[$index][1]}</td>
									<td class="höger">{$this->tt->utfallshistorik[$index][2]}</td>
									<td class="höger vinst10">{$this->tt->utfallshistorik[$index][3]}</td>
								</tr>

EOT;
	}
}
