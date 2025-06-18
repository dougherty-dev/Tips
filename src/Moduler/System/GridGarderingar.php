<?php

/**
 * Klass GridGarderingar.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\System;

use Tips\Moduler\System;

/**
 * Klass GridGarderingar.
 */
class GridGarderingar extends Matchtabeller {
	/**
	 * Garderingar för system.
	 */
	protected function grid_garderingar(): string {
		/**
		 * Hämta matchtabeller.
		 */
		[$garderingstabell, $oddstabell] = $this->matchtabeller();

		/**
		 * Iterera över garderingsfält.
		 */
		$th_rad = '';
		for ($j = 0; $j < self::SYSTEM_MAX_ANTAL_FÄLT; $j++) {
			$th_rad .= <<< EOT
								<th colspan="3" class="match"><input tabindex="-1" style="width: 3em;" type="number" min="0" max="13" step="1" name="andel_garderingar[$j]" value="{$this->andel_garderingar[$j]}">/{$this->antal_garderingar[$j]}</th>

EOT;
		}

		/**
		 * Returnera tabell.
		 */
		return <<< EOT
						<table id="system-garderingstabell" class="system-garderingstabell">
							<tr>
								<th class="match">#</th>
								<th class="match">Match</th>
								<th class="match">E</th>
$th_rad								<th colspan="3"><span id="system_antal_helgarderingar"></span> H | h <span id="system_antal_halvgarderingar"></span></th>
								<th class="match">🡄</th>
								<th class="match">🞭</th>
								<th class="match">R</th>
								<th class="match">🔻</th>
							</tr>
$garderingstabell						</table>
						<br>
						<table class="system-garderingstabell">
							<tr>
								<th class="match">#</th>
								<th class="match">Match</th>
								<th class="match">Res</th>
								<th colspan="3" class="odds">Odds</th>
								<th colspan="3" class="odds">Spektrum</th>
								<th colspan="3" class="streck">Streck</th>
								<th colspan="3" class="streck">Spektrum</th>
								<th colspan="4" class="match">Historik</th>
							</tr>
$oddstabell						</table>
EOT;
	}
}
