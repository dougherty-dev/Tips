<?php

/**
 * Klass TTGridOmgang.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

use Tips\Moduler\TT;

/**
 * Klass TTGridOmgang.
 */
final class TTGridOmgang extends Toppmeny {
	/**
	 * Initiera.
	 */
	public function __construct(private TT $tt) {
	}

	/**
	 * Grid för TT-omgång
	 */
	public function tt_grid_omgång(): string {
		[$datum, $spelstopp, $dag] = spelstopp($this->tt->spelstopp);

		/**
		 * Bygg rullgardinsmeny: Topptips, Topptips Europa, Topptips Stryk
		 */
		$typsträng = '';
		foreach ($this->tt::TOPPTIPSTYPER['namn'] as $typ) {
			$topptips_vald = ($this->tt->typer['typ'] === $typ) ? ' selected="selected"' : '';
			$typsträng .= t(10, "<option value=\"$typ\"$topptips_vald>$typ</option>");
		}

		/**
		 * Skicka tabell.
		 */
		return <<< EOT
{$this->toppmeny($typsträng)}
						<table class="omgångstabell">
							<tr><th>Omgång</th>
								<td>{$this->tt->omgång}</td>
								<td class="topp" rowspan="6">
									<p><label for="tt_strategi">Strategi</label></p>
									<textarea rows="6" cols="40" id="tt_strategi">{$this->tt->strategi}</textarea>
								</td></tr>
							<tr><th>Omsättning</th>
								<td>{$this->tt->omsättning}</td></tr>
							<tr><th>Spelstopp</th>
								<td>$dag $datum <input type="hidden" name="tt_spelstopp" value="{$this->tt->spelstopp}">$spelstopp</td>
							</tr><tr>
								<th>Överskjutande</th><td>{$this->tt->överskjutande} kr</td></tr>
							<tr><th>Extrapengar</th>
								<td>{$this->tt->extrapengar} kr</td></tr>
							<tr><th>Antal rader</th>
								<td><input class="nummer" type="number" min="20" max="1000" step="10" autocomplete="off" id="tt_antal_rader" value="{$this->tt->antal_rader}"></td></tr>
						</table>
EOT;
	}
}
