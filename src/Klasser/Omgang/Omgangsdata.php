<?php

/**
 * Klass Omgangsdata.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Omgang;

use Tips\Klasser\Tips;

/**
 * Klass Omgangsdata.
 * Visa omgång, år, vecka och tipsrad.
 * Visa utdelning och vinnare i respektive vinstgrupp.
 * Visa kontrollknappar.
 */
class Omgangsdata {
	/**
	 * Initiera.
	 */
	public function __construct(public Tips $tips) {
	}

	/**
	 * Tabelldata.
	 */
	protected function tabelldata(): string {
		/**
		 * Skicka till GridOmgangsdata.
		 */
		return <<< EOT
								<tr>
									<th>Omgång</th>
									<th>År</th>
									<th>Vecka</th>
									<th>Tipsrad</th>
								</tr>
								<tr>
									<td><input type="text" pattern="[0-9]" id="omgång" name="omgång" size="8" value="{$this->tips->spel->omgång}"></td>
									<td><input type="text" pattern="[0-9]" id="år" name="år" size="8" value="{$this->tips->utdelning->år}"></td>
									<td><input type="text" pattern="[0-9]" id="vecka" name="vecka" size="8" value="{$this->tips->utdelning->vecka}"></td>
									<td><input type="text" pattern="[1X2]" id="tipsrad" name="tipsrad" size="15" value="{$this->tips->utdelning->tipsrad}"></td>
								</tr>
								<tr>
									<th>13 rätt</th>
									<th>12 rätt</th>
									<th>11 rätt</th>
									<th>10 rätt</th>
								</tr>
								<tr>
									<td><input type="text" pattern="[0-9]" name="utdelning[]" size="8" value="{$this->tips->utdelning->utdelning[0]}"></td>
									<td><input type="text" pattern="[0-9]" name="utdelning[]" size="8" value="{$this->tips->utdelning->utdelning[1]}"></td>
									<td><input type="text" pattern="[0-9]" name="utdelning[]" size="8" value="{$this->tips->utdelning->utdelning[2]}"></td>
									<td><input type="text" pattern="[0-9]" name="utdelning[]" size="8" value="{$this->tips->utdelning->utdelning[3]}"></td>
								</tr>
								<tr>
									<th># 13 r</th>
									<th># 12 r</th>
									<th># 11 r</th>
									<th># 10 r</th>
								</tr>
								<tr>
									<td><input type="text" pattern="[0-9]" name="vinnare[]" size="8" value="{$this->tips->utdelning->vinnare[0]}"></td>
									<td><input type="text" pattern="[0-9]" name="vinnare[]" size="8" value="{$this->tips->utdelning->vinnare[1]}"></td>
									<td><input type="text" pattern="[0-9]" name="vinnare[]" size="8" value="{$this->tips->utdelning->vinnare[2]}"></td>
									<td><input type="text" pattern="[0-9]" name="vinnare[]" size="8" value="{$this->tips->utdelning->vinnare[3]}"></td>
								</tr>
EOT;
	}
}
