<?php

/**
 * Klass GridOmgangsdata.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Omgang;

use Tips\Klasser\Tips;
use Tips\Klasser\DBPreferenser;

/**
 * Klass GridOmgangsdata.
 * Visa spelstopp och sekvens.
 * Visa strategi och kommentar.
 * Visa omgång, år, vecka och tipsrad.
 * Visa utdelning och vinnare i respektive vinstgrupp.
 * Visa kontrollknappar.
 */
final class GridOmgangsdata extends Omgangsdata {
	/**
	 * Initiera.
	 */
	public function __construct(public Tips $tips) {
	}

	/**
	 * Visa omgångsdata.
	 */
	public function visa(): string {
		$sekvenser = implode(', ', $this->tips->utdelning->spel->sekvenser);
		$strategi = (new DBPreferenser($this->tips->spel->db))->hämta_preferens('strategi');
		[$datum, $spelstopp, $dag] = spelstopp($this->tips->matcher->spelstopp);

		return <<< EOT
							<table class="omgångstabell">
								<tr>
									<td colspan="3" class="ramfri">$dag <span id="datumtid">$datum $spelstopp</span><input type="hidden" id="spelstopp" name="spelstopp" value="{$this->tips->matcher->spelstopp}"></td>
									<td colspan="1"><strong>Sekv. {$this->tips->utdelning->spel->sekvens} ∈ [$sekvenser]</strong></td>
									<td id="omgång_strategi" class="topp" rowspan="7">
										<p id="omgång_strategi_p"><label for="strategi">Strategi</label></p>
										<textarea class="mindre" rows="8" cols="45" id="strategi">$strategi</textarea>
									</td>
									<td id="omgång_kommentar" class="topp" rowspan="7" hidden>
										<p id="omgång_kommentar_p"><label for="omgång_kommentar_area">Kommentar</label></p>
										<textarea class="mindre" disabled rows="8" cols="45" id="omgång_kommentar_area">{$this->tips->spelade->kommentar}</textarea>
									</td>
								</tr>
{$this->tabelldata()}							</table>
							<br>
							<button id="spara_omgång" value="spara_omgång">Spara</button>
							<button id="hämta_tipsresultat" value="hämta_tipsresultat">Resultat</button>
							<button id="hämta_tipsdata" value="hämta_tipsdata">Tipsdata</button>
							<button id="radera_omgång" value="radera_omgång">Radera</button>
							<p><span id="tidsangivelse"></span></p>
EOT;
	}
}
