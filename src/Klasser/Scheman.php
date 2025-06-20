<?php

/**
 * Klass Scheman.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

use Tips\Klasser\Scheman\Hamta;

/**
 * Klass Scheman.
 * Förinställda konfigurationer för olika moduler med värden.
 */
final class Scheman extends Hamta {
	public function __construct(protected Tips $tips) {
		parent::__construct($this->tips);
		$this->hämta_scheman();
		$this->visa_scheman();
	}

	/**
	 * Visa scheman.
	 */
	public function visa_scheman(): void {
		$db_preferenser = new DBPreferenser($this->tips->spel->db);

		$aktiva_moduler = array_keys($this->tips->moduler->moduldata);

		$scheman = '';
		foreach ($this->scheman as $id => $schema) {
			$schemarad = '';
			$schemamoduler = [];

			$data = $this->extrahera_namn($schema['data']);

			/**
			 * Lägg till rader för aktiva moduler.
			 */
			foreach (explode(',', $schema['faktorer']) as $faktor) {
				[$modul, $attraktionsfaktor] = explode(':', $faktor);
				$schemamoduler[] = $modul;

				[$klass, $radera] = match (in_array($modul, $aktiva_moduler)) {
					true => ['', ''],
					false => [' class="streck radera"', '❌ ']
				};

				$schemarad .= $this->schemarad($modul, $attraktionsfaktor, $klass, $radera);
			}

			/**
			 * Lägg till rader för nyaktiverade moduler.
			 */
			foreach ($aktiva_moduler as $modul) {
				if (!in_array($modul, $schemamoduler)) {
					$attraktionsfaktor = $db_preferenser->hämta_preferens(mb_strtolower($modul) . '.attraktionsfaktor');
					$schemarad .= $this->schemarad($modul, $attraktionsfaktor);
				}
			}

			/**
			 * Lägg samman rader.
			 */
			$scheman .= $this->enskilt_schema($id, $data, $schemarad);
		}

			/**
			 * Eka ut tabeller.
			 */
		echo $this->schema($scheman);
	}

	/**
	 * Extrahera schemanamn.
	 * @return string[] $data
	 */
	private function extrahera_namn(string $schema_data): array {
		/**
		 * Associativt fält $data[$namn]:
		 * [schema_namn] => R_4_5_238_1 F2/4
		 * [schema_antal_rader] => 100
		 */
		$data = [];
		foreach (explode(',', $schema_data) as $dat) {
			[$namn, $data[$namn]] = explode(':', $dat);
		}
		return $data;
	}
}
