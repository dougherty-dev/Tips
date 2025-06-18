<?php

/**
 * Klass Matchtabell.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\HG;

use Tips\Egenskaper\Eka;

/**
 * Klass Matchtabell.
 * Visa HG-rad, tipsrad, matcher och matchresultat.
 * Visa odds och justerade odds, samt utfallshistorik.
 */
class Matchtabell extends Prova {
	use Eka;

	/**
	 * Eka ut matchtabell.
	 * Nyttjas för matchanalys med halvgarderingar.
	 */
	protected function matchtabell(
		int $index, // tabellrad
		string $hgstil, // röd förlust, grön vinst eller odefinierad
		string $hg_tecken, // tecken för HG
		string $vinsttecken // teckenresultat
	): string {
		/**
		 * Returnera tabell till Omgang.
		 */
		return <<< EOT
							<tr>
								<td class="match höger">{$this->eka(strval($index + 1))}</td>
								<td class="{$hgstil}center">$hg_tecken</td>
								<td class="vinstrad">$vinsttecken</td>
								<td{$this->odds->sorteringsstil[$index]} class="mindre">{$this->matcher->match[$index]}</td>
								<td{$this->odds->sorteringsstil[$index]}>{$this->odds->sortering[$index]}</td>
								<td class="mindre">{$this->matcher->resultat[$index]}</td>
								{$this->odds->stil_sannolikheter[$index][0]}
								{$this->odds->stil_sannolikheter[$index][1]}
								{$this->odds->stil_sannolikheter[$index][2]}
								{$this->odds->justerade_pred->justerade_stilade[$index][0]}
								{$this->odds->justerade_pred->justerade_stilade[$index][1]}
								{$this->odds->justerade_pred->justerade_stilade[$index][2]}
								<td class="höger">{$this->odds->utfallshistorik->utfallshistorik[$index][0]}</td>
								<td class="höger">{$this->odds->utfallshistorik->utfallshistorik[$index][1]}</td>
								<td class="höger">{$this->odds->utfallshistorik->utfallshistorik[$index][2]}</td>
								<td class="höger vinst10">{$this->odds->utfallshistorik->utfallshistorik[$index][3]}</td>
							</tr>

EOT;
	}
}
