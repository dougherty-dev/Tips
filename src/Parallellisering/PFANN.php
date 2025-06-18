<?php

/**
 * Klass PFANN.
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
use Tips\Klasser\DBPreferenser;
use Tips\Moduler\FANN;

require_once dirname(__FILE__) . '/../../vendor/autoload.php';
new Preludium();

/**
 * Klass PFANN.
 * Generera FANN under parallell exekvering.
 */
final class PFANN {
	private Spel $spel;
	private Tips $tips;
	private FANN $fann;
	private DBPreferenser $db_preferenser;

	/**
	 * Initiera.
	 */
	public function __construct() {
		$this->spel = new Spel();
		$this->tips = new Tips($this->spel);
		$this->db_preferenser = new DBPreferenser($this->spel->db);
		$this->fann = new FANN(
			$this->tips->utdelning,
			$this->tips->odds,
			$this->tips->streck,
			$this->tips->matcher
		);
		$this->pfann();
	}

	/**
	 * Parallellisera FANN.
	 */
	private function pfann(): void {
		/**
		 * Hämta sparade data från databas.
		 */
		$bästa_lösning = array_map(
			'floatval',
			explode(',', $this->db_preferenser->hämta_preferens('fann.parametrar'))
		);

		$oddssannolikheter = återbygg_matris(
			$this->db_preferenser->hämta_preferens('parallellisering.oddssannolikheter', 'temp')
		);

		$strecksannolikheter = återbygg_matris(
			$this->db_preferenser->hämta_preferens('parallellisering.strecksannolikheter', 'temp')
		);

		$tipsrader = explode(',', $this->db_preferenser->hämta_preferens('parallellisering.tipsrader', 'temp'));

		$partitioner = $this->db_preferenser->hämta_preferens('parallellisering.partitioner', 'temp');
		$mängder = återbygg_matris(
			$this->db_preferenser->hämta_preferens('parallellisering.mängder', 'temp'),
			3,
			(int) $partitioner
		);

		/**
		 * Iterera fram bästa lösning.
		 */
		$r_bästa_lösning = 0;
		$vektorer = extrahera(); // hämta parametrar från querysträng.
		foreach ($mängder[vektorprodukt($vektorer, [1, 3, 9, 27])] as $limiter) {
			$summa_antal_rätt = 0;
			/**
			 * Över samtliga kompletta tipsrader.
			 */
			foreach ($tipsrader as $k => $tipsrad_012) {
				$rådata = [];
				/**
				 * Över varje match.
				 */
				foreach ($oddssannolikheter[$k] as $j => $odds_s) {
					$streck_s = $strecksannolikheter[$k][$j];
					/**
					 * Kör FANN med kombination av odds och streck.
					 * Addera till rådata.
					 */
					$res = fann_run(
						$this->fann->fann,
						[$odds_s[0],
						$streck_s[0],
						$odds_s[1],
						$streck_s[1],
						$odds_s[2],
						$streck_s[2]]
					);
					$rådata[] = $res[0];
				}

				$summa_antal_rätt += antal_rätt($this->fann->beräkna_utdata($rådata, $limiter), $tipsrad_012);
			}

			/**
			 * Ny bästa lösning?
			 */
			if ($summa_antal_rätt > $r_bästa_lösning) {
				$bästa_lösning = $limiter;
				$r_bästa_lösning = $summa_antal_rätt;
			}
		}

		/**
		 * Spara resultat för vidare behandling.
		 */
		$data = implode(',', [$r_bästa_lösning, implode(',', $bästa_lösning)]);
		$this->tips->parallellisering->populera_databas($data, $vektorer);
	}
}

new PFANN();
