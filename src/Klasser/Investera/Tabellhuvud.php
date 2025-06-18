<?php

/**
 * Klass Tabellhuvud.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Investera;

/**
 * Klass Tabellhuvud.
 */
final class Tabellhuvud {
	/**
	 * Definiera uppmärkning av tabellhuvud.
	 */
	public function tabellhuvud(): string {
		return <<< EOT
						<table id="investeringstabell">
							<tr class="match">
								<th>#</th>
								<th>Tid</th>
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
