<?php

/**
 * Klass Generera.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\DistributionGenerera;

use PDO;
use Tips\Klasser\Tips;
use Tips\Moduler\Distribution;
use Tips\Egenskaper\Varden;

/**
 * Klass Generera.
 */
final class Generera extends Graf {
	use Varden;

	/**
	 * @var int[] $distribution
	 */
	protected array $distribution = [];

	/**
	 * Init.
	 */
	public function __construct(protected Tips $tips, protected Distribution $dist) {
		parent::__construct($tips, $dist);
		$this->hämta_värden($tips->spel->db);
		$this->parallellisera();
		$this->uppdatera_graf();
	}

	/**
	 * Behandla distributionsberäkning med parallellisering.
	 * @return array<string, int>
	 */
	private function behandla_parallellisering(): array {
		$this->tips->parallellisering->vänta_på_databas();

		$dist = [];
		$sats = $this->tips->odds->spel->db->temp->query("SELECT `val` FROM `parallellisering`");
		if ($sats !== false) {
			foreach ($sats->fetchAll(PDO::FETCH_ASSOC) as $rad) {
				$distribution = array_chunk(explode(',', $rad['val']), 2);

				/**
				 * Uppdatera distribution för enskild punkt.
				 */
				foreach ($distribution as [$summa, $antal_rader]) {
					match (isset($dist[$summa])) {
						true => $dist[$summa] += $antal_rader,
						false => $dist[$summa] = $antal_rader
					};
				}
			}
		}

		$this->tips->odds->spel->db->temp->exec("DELETE FROM `parallellisering`");
		return $dist;
	}

	/**
	 * Parallellisera.
	 */
	private function parallellisera(): void {
		if (!$this->tips->odds->komplett) {
			return;
		}

		$this->tips->parallellisering->parallellisera(PARALLELLISERING . '/PDistribution.php');
		$this->distribution = $this->behandla_parallellisering();
		krsort($this->distribution); // distribution av sannolikhetssummor för odds
	}
}
