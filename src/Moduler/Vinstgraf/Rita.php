<?php

/**
 * Klass Rita.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Vinstgraf;

use Tips\Klasser\Graf;
use Tips\Klasser\DBPreferenser;
use Tips\Klasser\Utdelning;
use Tips\Klasser\Prediktioner;
use Tips\Klasser\Matcher;

/**
 * Klass Rita.
 */
class Rita {
	use Konstanter;

	protected Graf $graf;
	protected DBPreferenser $db_preferenser;
	public int $utdelning_13_min = self::UTDELNING_13_MIN_STD;
	public int $utdelning_13_max = self::UTDELNING_13_MAX_STD;

	public function __construct(
		protected Utdelning $utdelning,
		protected Prediktioner $odds,
		protected Prediktioner $streck,
		protected Matcher $matcher
	) {
		$this->db_preferenser = new DBPreferenser($this->utdelning->spel->db);
		$this->graf = new Graf();
		$this->utdelning_13_min = (int) $this->db_preferenser->hämta_preferens('vinstgraf.utdelning_13_min');
		$this->utdelning_13_max = (int) $this->db_preferenser->hämta_preferens('vinstgraf.utdelning_13_max');
	}

	/**
	 * Rita punkt för vinstrad i projicerat koordinatsystem.
	 * Högvinst i rött eler blått, övriga i gul nyans.
	 */
	protected function rita(string $tipsrad_012, int $utdelning): void {
		[$x, $y] = $this->graf->tipsgrafskoordinater($tipsrad_012);
		$färg = match (true) {
			$utdelning >= self::UTDELNING_13_MIN_MAX => $this->graf->blå,
			$utdelning >= $this->utdelning_13_max => $this->graf->röd,
			default => $this->graf->gul
		};
		$this->graf->sätt_pixel($x, $y, $färg);
	}
}
