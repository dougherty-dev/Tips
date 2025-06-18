<?php

/**
 * Klass THRad.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Investera;

/**
 * Klass THRad.
 */
final class THRad {
	/**
	 * Definiera uppmärkning av tabellhuvud.
	 */
	public function th_rad(): string {
		return <<< EOT
							<tr class="match">
								<th>Omgång</th>
								<th>Typ</th>
								<th>Sekvens</th>
								<th>Valda</th>
								<th>Genererade</th>
								<th>Andel %</th>
								<th>Vinst</th>
								<th>13r</th>
								<th>12r</th>
								<th>11r</th>
								<th>10r</th>
							</tr>

EOT;
	}
}
