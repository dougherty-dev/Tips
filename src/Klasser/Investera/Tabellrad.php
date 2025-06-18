<?php

/**
 * Klass Tabellrad.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Investera;

/**
 * Klass Tabellrad.
 */
class Tabellrad extends Statistik {
	/**
	 * Bygg tabellrad.
	 * @param int[] $rad
	 * @param int[] $vinstdata
	 */
	protected function tabellrad(int &$antal, array $rad, array $vinstdata): void {
		$andel = number_format(100 * $rad['valda'] / $rad['genererade'], 2);

		/**
		 * Räkna ned antal och visa enskild post.
		 */
		if ($antal-- <= $this->visa_antal) { // Visa n poster
			$inv_nr = $this->antal_investeringar - $antal; // Räkna ned från summerat antal
			[$s13, $s12, $s11, $s10] = $this->vinststil($vinstdata); // Stila vinstdata i färger

			/**
			 * Bygg tabellrad.
			 */
			$this->tabell .=  <<< EOT
						<tr class="höger">
							<td>$inv_nr</td>
							<td>{$rad['tid']}</td>
							<td>{$rad['omgång']}</td>
							<td>{$rad['speltyp']}</td>
							<td>{$rad['sekvens']}</td>
							<td>{$rad['valda']}</td>
							<td>{$rad['genererade']}</td>
							<td>$andel</td>
							<td class="vinstrad"><strong>{$rad['vinst']}</strong></td>
							<td$s13>{$vinstdata[13]}</td>
							<td$s12>{$vinstdata[12]}</td>
							<td$s11>{$vinstdata[11]}</td>
							<td$s10>{$vinstdata[10]}</td>
						</tr>

EOT;
		}
	}
}
