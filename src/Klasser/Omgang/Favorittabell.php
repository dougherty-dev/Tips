<?php

/**
 * Klass Favorittabell.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Omgang;

use Tips\Klasser\Tips;
use Tips\Egenskaper\Eka;

/**
 * Klass Favorittabell.
 */
class Favorittabell {
	/**
	 * Visa favoriter, sorterade fallande.
	 */
	public function favorittabell(string $grid_favoriter): string {
		/**
		 * Returnera tabell.
		 */
		return <<< EOT
							<table id="favorittabell">
								<caption class="match ram fet">Favoriter (historik)</caption>
								<tr class="match">
									<th>#</th>
									<th>P</th>
									<th>O</th>
									<th>Match</th>
									<th colspan="4">Historik</th>
									<th>Res</th>
									<th>T</th>
								</tr>
$grid_favoriter							</table>
							<br>
EOT;
	}
}
