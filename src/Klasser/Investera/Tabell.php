<?php

/**
 * Klass Tabell.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Investera;

/**
 * Klass Tabell.
 */
class Tabell extends PlottaAck {
	/**
	 * Eka ut tabell.
	 * Enskild tabell med momentana data för omgång.
	 */
	protected function tabell(int|string $andel, string $s13, string $s12, string $s11, string $s10): string {
		$th_rad = (new THRad())->th_rad();

		return <<< EOT
						<p>Investerat i omgång {$this->tips->utdelning->spel->omgång}:</p>
						<table>
$th_rad 							<tr class="höger">
								<td>{$this->tips->utdelning->spel->omgång}</td>
								<td>{$this->tips->utdelning->spel->speltyp->produktnamn()}</td>
								<td>{$this->tips->utdelning->spel->sekvens}</td>
								<td>{$this->antal_utvalda_rader}</td>
								<td>{$this->antal_genererade}</td>
								<td>$andel</td>
								<td>{$this->vinst}</td>
								<td$s13>{$this->vinstdata[13]}</td>
								<td$s12>{$this->vinstdata[12]}</td>
								<td$s11>{$this->vinstdata[11]}</td>
								<td$s10>{$this->vinstdata[10]}</td>
							</tr>
						</table>

EOT;
	}
}
