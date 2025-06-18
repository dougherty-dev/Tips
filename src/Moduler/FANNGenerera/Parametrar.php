<?php

/**
 * Klass Parametrar.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\FANNGenerera;

use PDO;
use Tips\Klasser\Parallellisering;

/**
 * Klass Parametrar.
 * Generera parametrar för FANN.
 */
class Parametrar extends MonteCarlo {
	private Parallellisering $parallellisering;
	public bool $exists_fann = false;
	protected int $antal_lager = 2;
	protected int $max_neuroner = 10000;

	/**
	 * Generera parametrar med Monte Carlo-metod.
	 */
	protected function generera_parametrar(): void {
		/**
		 * Kräv anslutning till neuralt nätverk.
		 */
		if (!$this->exists_fann) {
			return;
		}

		/**
		 * Initiera parallell behandling.
		 */
		$this->parallellisering = new Parallellisering($this->fann->odds->spel);

		/**
		 * Partitionera datamängd.
		 */
		$partitioner = intval($this->mc_antal_punkter / $this->trådar);
		$mängder = array_chunk($this->monte_carlo(), max(1, abs($partitioner)));

		/**
		 * Spara oddssannolikheter med mera till temporär databas.
		 * Hämtas sedermera av bakgrundsskript i partitionerade segment.
		 */
		$this->fann->db_preferenser->spara_preferens(
			'parallellisering.oddssannolikheter',
			implode(',', platta_matris($this->oddssannolikheter)),
			'temp'
		);

		$this->fann->db_preferenser->spara_preferens(
			'parallellisering.strecksannolikheter',
			implode(',', platta_matris($this->strecksannolikheter)),
			'temp'
		);

		$this->fann->db_preferenser->spara_preferens(
			'parallellisering.tipsrader',
			implode(',', $this->tipsrader),
			'temp'
		);

		$this->fann->db_preferenser->spara_preferens(
			'parallellisering.mängder',
			implode(',', platta_matris($mängder)),
			'temp'
		);

		$this->fann->db_preferenser->spara_preferens(
			'parallellisering.partitioner',
			(string) $partitioner,
			'temp'
		);

		/**
		 * Påbörja parallell behandling.
		 */
		$this->parallellisering->parallellisera(PARALLELLISERING . '/PFANN.php');
		$bästa_lösning = $this->behandla_parallellisering();

		$this->fann->db_preferenser->spara_preferens(
			'fann.parametrar',
			implode(',', $bästa_lösning)
		);
	}

	/**
	 * Ta fram parametrar med parallella processer.
	 * @return float[]
	 */
	private function behandla_parallellisering(): array {
		$this->parallellisering->vänta_på_databas();

		$antal_rätt_bästa = 0;
		$bästa_lösning = [];
		$sats = $this->fann->odds->spel->db->temp->query("SELECT `val` FROM `parallellisering`");
		if ($sats !== false) {
			foreach ($sats->fetchAll(PDO::FETCH_ASSOC) as $r) {
				$lösning = array_map('floatval', explode(',', $r['val']));
				$summa_antal_rätt = array_shift($lösning);
				if ($summa_antal_rätt > $antal_rätt_bästa) {
					$antal_rätt_bästa = $summa_antal_rätt;
					$bästa_lösning = $lösning;
				}
			}
		}

		/**
		 * Rensa temporär databas.
		 */
		$this->fann->odds->spel->db->temp->exec("DELETE FROM `parallellisering`");
		$this->fann->odds->spel->db->temp->exec("DELETE FROM `preferenser`");

		return $bästa_lösning;
	}
}
