<?php

/**
 * Klass PGenerera.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Parallellisering;

/**
 * Kontrollera inte nya moduler under parallell exekvering.
 */
define('GENERERA', true);

use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\Tips;
use Tips\Egenskaper\Varden;

require_once dirname(__FILE__) . '/../../vendor/autoload.php';
new Preludium();

/**
 * Klass PGenerera.
 * Generera tipsrader under parallell exekvering.
 */
final class PGenerera {
	use Varden;

	private Spel $spel;
	private Tips $tips;

	/**
	 * Initiera.
	 */
	public function __construct() {
		$this->spel = new Spel();
		$this->tips = new Tips($this->spel);
		$this->hämta_värden($this->spel->db);
		$this->pgenerera();
	}

	/**
	 * Parallellisera generering av tipsrader.
	 */
	private function pgenerera(): void {
		$vektorer = extrahera(); // hämta parametrar från querysträng.
		$tipsvektor = []; // håll tipsrader i ett fält.

		/**
		 * Definiera en rymd.
		 */
		$delrymd = [];
		for ($index = 4; $index >= 1; $index--) {
			$delrymd[$index] = ($this->trådar >= 3 ** $index) ? [$vektorer[$index - 1]] : TECKENRYMD;
		}

		$rymd = [...array_fill(0, MATCHANTAL - 4, TECKENRYMD), ...$delrymd];

		/**
		 * Hämta moduldata.
		 */
		$moduler = $this->hämta_moduler();

		/**
		 * Använd generator för att generera tipsrader.
		 * Kontrollera mot varje modulmetod eller fortsätt.
		 */
		foreach (generera($rymd) as $tipsrad_012) {
			foreach ($moduler as $modul) {
				if (!method_exists($modul, 'pröva_tipsrad') || !$modul->pröva_tipsrad($tipsrad_012)) {
					continue 2;
				}
			}
			$tipsvektor[] = base_convert($tipsrad_012, 3, 36);
		}

		$this->tips->parallellisering->populera_databas(implode(',', $tipsvektor), $vektorer);
	}

	/**
	 * Hämta moduler.
	 * @return object[]
	 */
	private function hämta_moduler(): array {
		$moduler = [];

		/**
		 * Ladda moduler med en metod för att pröva tipsrader.
		 */
		foreach ($this->tips->moduler->skarpa_moduler as $modul) {
			$klass = "\\Tips\\Moduler\\$modul";
			$ny_modul = new $klass($this->tips->utdelning, $this->tips->odds, $this->tips->streck, $this->tips->matcher);
			if (method_exists($ny_modul, 'pröva_tipsrad')) {
				$moduler[] = $ny_modul;
			}
		}

		return $moduler;
	}
}

new PGenerera();
