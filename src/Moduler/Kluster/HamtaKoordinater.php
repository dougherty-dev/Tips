<?php

/**
 * Klass HamtaKoordinater.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Kluster;

use Tips\Klasser\Utdelning;
use Tips\Klasser\Prediktioner;
use Tips\Klasser\Matcher;
use Tips\Klasser\Graf;
use Tips\Klasser\DBPreferenser;
use Tips\Egenskaper\Varden;

/**
 * Klass HamtaKoordinater.
 */
class HamtaKoordinater {
	use Konstanter;
	use Varden;

	public DBPreferenser $db_preferenser;
	protected Graf $graf;
	protected int $min_radie = self::KLUSTER_MIN_RADIE_STD;
	protected int $min_antal = self::KLUSTER_MIN_ANTAL_STD;
	protected int $antal_rader = 0;
	protected int $klustrade_rader = 0;
	protected int $area = 0;
	public string $kombinationsgraf = '/kluster.png';
	/**
	 * @var array<int, int[]> $rektanglar
	 */
	protected array $rektanglar = [];

	public function __construct(
		protected Utdelning $utdelning,
		protected Prediktioner $odds,
		protected Prediktioner $streck,
		protected Matcher $matcher
	) {
		$this->db_preferenser = new DBPreferenser($this->odds->spel->db);
		$this->hämta_värden($this->odds->spel->db);
		$this->graf = new Graf();
	}
	/**
	 * Hämta koordinater fråb databas.
	 * @return array<int, int[]>
	 */
	protected function hämta_koordinater(): array {
		$koordinater = [];
		$tipsdata = $this->odds->tipsdata($this->u13_min, $this->u13_max);

		foreach ($tipsdata as $tipsrad_012) {
			[$x, $y] = $this->graf->tipsgrafskoordinater($tipsrad_012);
			$koordinater[] = [$x, $y];
			$this->graf->sätt_pixel($x, $y, $this->graf->gul);
		}

		return $koordinater;
	}
}
