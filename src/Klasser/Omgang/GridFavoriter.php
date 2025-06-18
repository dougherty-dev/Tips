<?php

/**
 * Klass GridFavoriter.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Omgang;

use Tips\Klasser\Tips;
use Tips\Egenskaper\Eka;

/**
 * Klass GridFavoriter.
 * Tabell med matcher sorterade efter favoritskap.
 * Favoriter definieras av historiskt utfall för oddsen i fråga.
 * I kontrast står favoriter under aktuella odds, de överensstämmer sällan.
 */
final class GridFavoriter extends Favorittabell {
	use Eka;

	/**
	 * Initiera.
	 */
	public function __construct(private Tips $tips) {
	}

	/**
	 * Visa favoriter, sorterade fallande.
	 */
	public function visa(): string {
		$grid_favoriter = '';
		$stil = ' class="odefinierad"';
		$hist_utfall = streck_till_sannolikheter($this->tips->odds->utfallshistorik->utfallshistorik);

		/**
		 * Iterera över utfallshistorik.
		 */
		foreach (array_keys($this->tips->odds->utfallshistorik->ordnad_historik) as $i) {
			if ($this->tips->utdelning->har_tipsrad) {
				$stil = rstil(
					$this->tips->odds->sannolikheter[$i][(int) $this->tips->utdelning->tipsrad_012[$i]],
					$this->tips->odds->maxsannolikheter[$i],
					$this->tips->odds->minsannolikheter[$i]
				);
			}

			/**
			 * Rendera visuellt i gråskala.
			 */
			$hstil = [
				stil($hist_utfall[$i][0]),
				stil($hist_utfall[$i][1]),
				stil($hist_utfall[$i][2])
			];

			/**
			 * Eka ut favotitabell.
			 */
			$grid_favoriter .= <<< EOT
								<tr>
									<td{$this->tips->odds->sorteringsstil[$i]}>{$this->eka(strval($i + 1))}</td>
									<td$stil>{$this->eka(number_format($this->tips->odds->maxsannolikheter[$i], 2))}</td>
									<td{$this->tips->odds->sorteringsstil[$i]}>{$this->eka(number_format($this->tips->odds->maxprediktioner[$i], 2))}</td>
									<td class="vänster"{$this->tips->odds->sorteringsstil[$i]}>{$this->tips->matcher->match[$i]}</td>
									<td{$hstil[0]}>{$this->tips->odds->utfallshistorik->utfallshistorik[$i][0]}</td>
									<td{$hstil[1]}>{$this->tips->odds->utfallshistorik->utfallshistorik[$i][1]}</td>
									<td{$hstil[2]}>{$this->tips->odds->utfallshistorik->utfallshistorik[$i][2]}</td>
									<td class="höger vinst10">{$this->tips->odds->utfallshistorik->utfallshistorik[$i][3]}</td>
									<td>{$this->tips->matcher->resultat[$i]}</td>
									<td$stil>{$this->eka($this->tips->utdelning->har_tipsrad ? $this->tips->utdelning->tipsrad[$i] : ' ')}</td>
								</tr>

EOT;
		}

		/**
		 * Returnera tabell.
		 */
		return $this->favorittabell($grid_favoriter);
	}
}
