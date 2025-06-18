<?php

/**
 * Klass PDistribution.
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
 * Klass PDistribution.
 * Generera distribution under parallell exekvering.
 */
final class PDistribution {
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
		$this->pdistribution();
	}

	/**
	 * Parallelliserad beräkning av distribution.
	 */
	private function pdistribution(): void {
		$vektorer = extrahera(); // hämta parametrar från querysträng.
		$distribution = [];
		$dist = [];

		/**
		 * Definiera en rymd.
		 */
		$delrymd = [];
		for ($index = 4; $index >= 1; $index--) {
			$delrymd[$index] = ($this->trådar >= 3 ** $index) ? [$vektorer[$index - 1]] : TECKENRYMD;
		}
		$rymd = [...array_fill(0, MATCHANTAL - 4, TECKENRYMD), ...$delrymd];

		/**
		 * Använd generator för att generera tipsrader.
		 * Summera sannolikheter över varje rad.
		 */
		foreach (generera($rymd) as $tipsrad_012) {
			$summa = 0.0;
			foreach ($this->tips->odds->sannolikheter as $k => $odds) {
				$summa += $odds[$tipsrad_012[$k]];
			}

			$summa_formaterad = number_format($summa, 2);
			$dist[$summa_formaterad] = isset($dist[$summa_formaterad]) ? $dist[$summa_formaterad] + 1 : 1;
		}

		$distribution = array_map(fn ($k): string => implode(',', [$k, $dist[$k]]), array_keys($dist));

		$this->tips->parallellisering->populera_databas(implode(',', $distribution), $vektorer);
	}
}

new PDistribution();
