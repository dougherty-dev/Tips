<?php

/**
 * Klass Moduler.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

use Tips\Klasser\Moduler\Kombinationsgraf;

/**
 * Klass Moduler.
 * Hantera insticksmoduler.
 * Moduler kan göra vad som helst, men interagerar normalt med systemet.
 * Parametrar för moduler är utdelning, odds, streck och matcher.
 */
final class Moduler extends Kombinationsgraf {
	/**
	 * Mall för moduler.
	 */
	public function visa_moduler(): void {
		$modultext = '';
		/**
		 * Generera lista över befintliga moduler.
		 * Knapp anger om modul är aktiv eller inte.
		 * Även en ordning kan definieras genom dra och släpp med jQueryUI.
		 */
		foreach ($this->moduler as $index => $modul) {
			$aktiv = ($modul[2]) ? ' checked="checked"' : '';
			$modultext .= <<< EOT
								<tr class="modulsortering" id="moduler_{$modul[1]}">
									<td class="ramfri luft">{$modul[0]}</td>
									<td class="ramfri luft"><input type="checkbox" id="sortera_moduler$index" name="modul[{$modul[1]}][aktiv]" value="1"$aktiv>
									<label for="sortera_moduler$index">{$modul[1]}</label></td>
								</tr>

EOT;
		}

		/**
		 * Eka ut modulflik.
		 */
		$this->modulflik($modultext);

		/**
		 * Iterera över aktiva moduler.
		 * Visa modulsida om metod är definierad.
		 */
		foreach ($this->m_moduler as $modul) {
			if (method_exists($modul, 'visa_modul')) {
				$modul->visa_modul();
			}
		}
	}

	/**
	 * Rendera modulflik.
	 */
	private function modulflik(string $modultext): void {
		echo <<< EOT
			<div id="flikar-modul">
				<div class="generell-övre-grid">
					<div class="generell-övre">
						<h1>Moduler</h1>
						<form method="post" action="/#flikar-modul">
							<table class="ram fluff" id="sortera_moduler">
								<tr class="match">
									<th class="höger">Prio</th>
									<th>Modul</th>
								</tr>
$modultext							</table>
							<p><input name="uppdatera_moduler" type="submit" value="Uppdatera"></p>
						</form>
						<p>Ändra ordning genom att dra knappar till önskad position.<br>
						Aktivera och inaktivera genom att klicka på önskad knapp.</p>
					</div> <!-- generell-övre -->
				</div> <!-- generell-övre-grid -->
			</div> <!-- flikar-modul -->

EOT;
	}
}
