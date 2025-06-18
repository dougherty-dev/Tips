<?php

/**
 * Klass Oddstabellrad.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\System;

use Tips\Klasser\Prediktioner;
use Tips\Klasser\Matcher;

/**
 * Klass Oddstabellrad.
 * Undre tabell med matcher, odds, streck och historik.
 */
final class Oddstabellrad {
	/**
	 * Initiera.
	 */
	public function __construct(
		protected Prediktioner $odds,
		protected Prediktioner $streck,
		protected Matcher $matcher
	) {
	}

	/**
	 * Tabellrad för prediktionsdata.
	 * @param string[] $o_stil
	 * @param string[] $s_stil
	 * @param array<int, string[]> $odds
	 */
	public function oddstabellrad(int $index, array $o_stil, array $odds, array $s_stil): string {
		$matchindex = $index + 1;

		/**
		 * SKicka till Matchtabeller.
		 */
		return <<< EOT
							<tr>
								<td class="match höger ix2">$matchindex</td>
								<td{$this->odds->sorteringsstil[$index]} class="vänster">{$this->matcher->match[$index]}</td>
								<td class="mindre">{$this->matcher->resultat[$index]}</td>
								<td{$o_stil[0]}>{$odds[$index][0]}</td>
								<td{$o_stil[1]}>{$odds[$index][1]}</td>
								<td{$o_stil[2]}>{$odds[$index][2]}</td>
								{$this->odds->stil_sannolikheter[$index][0]}
								{$this->odds->stil_sannolikheter[$index][1]}
								{$this->odds->stil_sannolikheter[$index][2]}
								<td{$s_stil[0]}>{$this->streck->prediktioner[$index][0]}</td>
								<td{$s_stil[1]}>{$this->streck->prediktioner[$index][1]}</td>
								<td{$s_stil[2]}>{$this->streck->prediktioner[$index][2]}</td>
								{$this->streck->stil_sannolikheter[$index][0]}
								{$this->streck->stil_sannolikheter[$index][1]}
								{$this->streck->stil_sannolikheter[$index][2]}
								<td class="höger">{$this->odds->utfallshistorik->utfallshistorik[$index][0]}</td>
								<td class="höger">{$this->odds->utfallshistorik->utfallshistorik[$index][1]}</td>
								<td class="höger">{$this->odds->utfallshistorik->utfallshistorik[$index][2]}</td>
								<td class="höger vinst10">{$this->odds->utfallshistorik->utfallshistorik[$index][3]}</td>
							</tr>

EOT;
	}
}
