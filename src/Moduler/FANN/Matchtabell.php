<?php

/**
 * Klass Matchtabell.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\FANN;

use Tips\Egenskaper\Eka;

/**
 * Klass Matchtabell.
 * Rendera tabell med matcher, odds, streck och annan information.
 */
class Matchtabell extends Prova {
	use Eka;

	/**
	 * Eka ut matchtabell.
	 * Nyttjas för matchanalys enligt FANN.
	 */
	protected function matchtabell(
		int $index, // radnummer
		string $fannstil, // röd förlust, grön vinst eller neutral
		string $fanntecken, // de tecken som FANN har genererat för matchen
		string $vinsttecken // aktuell
	): string {
		/**
		 * Returnera tabell till Omgang.
		 */
		return <<< EOT
							<tr>
								<td class="match höger">{$this->eka(strval($index + 1))}</td>
								<td class="center">{$this->eka(number_format($this->rådata[$index], 2))}</td>
								<td$fannstil>$fanntecken</td>
								<td class="vinstrad">$vinsttecken</td>
								<td{$this->odds->sorteringsstil[$index]} class="mindre">{$this->matcher->match[$index]}</td>
								<td{$this->odds->sorteringsstil[$index]}>{$this->odds->sortering[$index]}</td>
								<td class="mindre">{$this->matcher->resultat[$index]}</td>
{$this->grupp($this->odds->stil_sannolikheter[$index])}
{$this->grupp($this->odds->justerade_pred->justerade_stilade[$index])}
{$this->grupp($this->streck->stil_sannolikheter[$index])}
{$this->grupp($this->streck->justerade_pred->justerade_stilade[$index])}
								<td class="höger">{$this->odds->utfallshistorik->utfallshistorik[$index][0]}</td>
								<td class="höger">{$this->odds->utfallshistorik->utfallshistorik[$index][1]}</td>
								<td class="höger">{$this->odds->utfallshistorik->utfallshistorik[$index][2]}</td>
								<td class="höger vinst10">{$this->odds->utfallshistorik->utfallshistorik[$index][3]}</td>
							</tr>

EOT;
	}

	/**
	 * Banta ned tabellutskriften med en hjälpfunktion.
	 * Returnerar en grupp om tre i ett fält.
	 * @param string[] $td_grupp
	 */
	private function grupp(array $td_grupp): string {
		return <<< EOT
								$td_grupp[0]
								$td_grupp[1]
								$td_grupp[2]
EOT;
	}
}
