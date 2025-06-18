<?php

/**
 * Klass Mallar.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Scheman;

/**
 * Klass Mallar.
 * Hantera schemamallar.
 */
class Mallar extends Schema {
	/**
	 * Enskilt schema.
	 * Samlar enskilda tabeller.
	 * @param array<string, string> $data
	 */
	protected function enskilt_schema(int $id, array $data, string $schemarad): string {
		return <<< EOT
							<form class="flytande glipa" id="schema-$id" method="post" action="/">
								<table class="flytande mindre">
									<caption class="odds ram fet">$id: <input type="text" name="schema_namn" size="40" autocomplete="off" value="{$data['schema_namn']}"></caption>
									<tr>
										<td>Antal rader</td>
										<td class="höger"><input name="schema_antal_rader" class="nummer" type="number" min="100" max="1594323" step="100" autocomplete="off" value="{$data['schema_antal_rader']}"></td>
									</tr>
									<tr>
										<th class="odds" colspan="2">Faktorer</th>
									</tr>
$schemarad									<tr>
										<td colspan="2">
											<p><button class="schema-tillämpa" id="schema-tillämpa-$id">▶️ Tillämpa</button>
												<button class="schema-uppdatera" id="schema-uppdatera-$id">✅ Uppdatera</button>
												<button class="schema-radera" id="schema-radera-$id">❌ Radera</button></p>
										</td>
									</tr>
								</table>
							</form>

EOT;
	}

	/**
	 * Rendera enskild modulrad (faktorer).
	 */
	protected function schemarad(
		string $modul,
		string $attraktionsfaktor,
		string $klass = '',
		string $radera = ''
	): string {
		/**
		 * Skicka tillbaka radsegment.
		 */
		return <<< EOT
									<tr$klass>
										<td>$radera$modul</td>
										<td class="höger"><input name="modul[$modul]" type="number" min="1" max="1594323" autocomplete="off" value="$attraktionsfaktor"></td>
									</tr>

EOT;
	}
}
