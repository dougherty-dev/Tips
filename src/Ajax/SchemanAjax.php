<?php

/**
 * Klass SchemanAjax.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Ajax;

use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\Tips;
use Tips\Egenskaper\Ajax;

/**
 * Ajaxanrop ligger utanför ordinarie ordning.
 */
require_once dirname(__FILE__) . '/../../vendor/autoload.php';
new Preludium();

/**
 * Klass SchemanAjax.
 * Hantera förinställda konfigurationer för moduler och värden.
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
 */
final class SchemanAjax extends Schemamall {
	use Ajax;

	private Spel $spel;
	private Tips $tips;

	/**
	 * Inititiera.
	 */
	public function __construct() {
		$this->spel = new Spel();
		$this->tips = new Tips($this->spel);
		$this->tips->moduler->annonsera_moduler();
		$this->förgrena();
	}

	/**
	 * Lägg till nytt schema.
	 * js/funktioner.js: nytt_schema
	 */
	private function nytt_schema(): void {
		$modulsträng = '';
		/**
		 * Iterera över moduler.
		 */
		foreach (array_keys($this->tips->moduler->moduldata) as $modul) {
			$attraktionsfaktor = $this->db_preferenser->hämta_preferens(mb_strtolower($modul) . '.attraktionsfaktor');
			$modulsträng .= <<< EOT
							<tr>
								<td>$modul</td>
								<td class="höger"><input name="modul[$modul]" class="nummer" type="number" min="1" max="1594323" autocomplete="off" value="$attraktionsfaktor"></td>
							</tr>

EOT;
		}

		/**
		 * Eka ut mall för nytt schema.
		 */
		$pref_max_rader = $this->db_preferenser->hämta_preferens("preferenser.max_rader");
		$this->schemamall($pref_max_rader, $modulsträng);
	}

	/**
	 * Tillämpa schema.
	 * js/funktioner.js: tillämpa_schema, id
	 */
	private function tillämpa_schema(): void {
		if (!is_string($_REQUEST['tillämpa_schema'])) {
			return;
		}

		/**
		 * Kontroll av indata.
		 */
		parse_str($_REQUEST['tillämpa_schema'], $sdata);
		$parsedata = (array) filter_var_array((array) $sdata['modul'], FILTER_SANITIZE_SPECIAL_CHARS);

		/**
		 * Hantera indata, iterera över modul.
		 */
		foreach ($parsedata as $modul => $attraktionsfaktor) {
			$preferensnamn = mb_strtolower($modul . '.attraktionsfaktor');
			$this->db_preferenser->spara_preferens($preferensnamn, (string) $attraktionsfaktor);

			/**
			 * Säkerställ tillåtna intervall.
			 */
			$attraktionsfaktor = (int) $attraktionsfaktor;
			$this->db_preferenser->preferens_i_intervall(
				$attraktionsfaktor,
				AF_MIN,
				AF_MAX,
				AF_STD,
				$preferensnamn
			);
		}
		unset($sdata['modul']);

		$data = [];
		foreach ($sdata as $k => $värde) {
			$data[$k] = (int) $värde;
		}

		/**
		 * Spara värden.
		 */
		$this->db_preferenser->spara_preferens('preferenser.max_rader', (string) $data['schema_antal_rader']);
		$this->db_preferenser->preferens_i_intervall(
			$data['schema_antal_rader'],
			MIN_RADER,
			MAX_RADER,
			MIN_RADER,
			'preferenser.max_rader'
		);
	}
}

new SchemanAjax();
