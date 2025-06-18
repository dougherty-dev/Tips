<?php

/**
 * Klass Schemamall.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Ajax;

/**
 * Klass Schemamall.
 */
class Schemamall {
	/**
	 * Eka ut mall för nytt schema.
	 */
	protected function schemamall(string $pref_max_rader, string $modulsträng): void {
		echo <<< EOT
				<div id="schemamall">
					<hr>
					<form id="schemadata" method="post" action="/">
						<table>
							<caption class="match ram fet"><input type="text" name="schema_namn" size="30" autocomplete="off" value="Nytt schema"></caption>
							<tr>
								<td>Antal rader</td>
								<td class="höger"><input name="schema_antal_rader" class="nummer" type="number" min="100" max="1594323" step="100" autocomplete="off" value="$pref_max_rader"></td>
							</tr>
							<tr><th class="match" colspan="2">Faktorer</th></tr>
$modulsträng							</table>
						<p><button id="schema_spara">Spara</button></p>
					</form>
				</div> <!-- schemamall -->

EOT;
	}
}
