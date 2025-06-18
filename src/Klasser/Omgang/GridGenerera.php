<?php

/**
 * Klass GridGenerera.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Omgang;

use Tips\Klasser\Tips;
use Tips\Egenskaper\Varden;

/**
 * Klass GridGenerera.
 */
final class GridGenerera {
	use Varden;

	/**
	 * Initiera.
	 */
	public function __construct(private Tips $tips) {
		$this->hämta_värden($this->tips->spel->db);
	}

	/**
	 * Visa grid.
	 */
	public function visa(): string {
		$sekvenssträng =  '';
		$grid_generera = '';

		/**
		 * Rullgardin för sekvenser.
		 */
		foreach ($this->tips->utdelning->spel->sekvenser as $sekvens) {
			$vald = ($this->tips->utdelning->spel->sekvens === $sekvens) ? ' selected="selected"' : '';
			$sekvenssträng .= t(8, "<option$vald>$sekvens</option>");
		}

		if (count($this->tips->utdelning->spel->sekvenser) > 1 || $this->tips->spelade->spelad) {
			$grid_generera = <<< EOT
						<p>
							<select id="sekvens">
$sekvenssträng							</select>
							<button id="radera_sekvens" value="{$this->tips->utdelning->spel->sekvens}">❌</button>
						</p>

EOT;
		}

		/**
		 * Knapp för att generera tipsrader.
		 */
		$grid_generera .= $this->tips->spelade->spelad ?
			rtrim(t(6, "<p><input type=\"submit\" value=\"➕ Ny sekvens\" id=\"ny_sekvens\"></p>")) :
			<<< EOT
						<form id="generera" method="post" action="/#flikar-genererat">
							<p><button class="generera" name="generera">⚽️ Generera {$this->max_rader}</button></p>
						</form>
EOT;

		return $grid_generera;
	}
}
