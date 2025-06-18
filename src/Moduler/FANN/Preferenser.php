<?php

/**
 * Klass Preferenser.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\FANN;

use Tips\Klasser\Utdelning;
use Tips\Klasser\Prediktioner;
use Tips\Klasser\Matcher;
use Tips\Klasser\DBPreferenser;
use Tips\Egenskaper\Tick;
use Tips\Moduler\FANN\Konstanter;

/**
 * Klass Preferenser.
 */
class Preferenser {
	use Tick;
	use Konstanter;

	public DBPreferenser $db_preferenser;
	/**
	 * @var float[] $limiter
	 */
	public array $limiter = [-0.55, -0.05, 0.43]; // 1, 1X, X2, Monte Carlo
	protected int $fann_min = 10;
	public float $feltolerans = 0.16;

	public function __construct(
		public Utdelning $utdelning,
		public Prediktioner $odds,
		public Prediktioner $streck,
		public Matcher $matcher
	) {
			$this->db_preferenser = new DBPreferenser($this->odds->spel->db);
	}

	/**
	 * Uppdatera preferenser.
	 */
	protected function uppdatera_preferenser(): void {
		$this->db_preferenser->int_preferens_i_intervall(
			$this->fann_min,
			self::FANN_MIN,
			self::FANN_MAX,
			self::FANN_STD,
			'fann.fann_min'
		);

		$this->db_preferenser->preferens_i_intervall(
			$this->feltolerans,
			self::FANN_FELTOLERANS_MIN,
			self::FANN_FELTOLERANS_MAX,
			self::FANN_FELTOLERANS_STD,
			'fann.fann_feltolerans'
		);

		$this->db_preferenser->int_preferens_i_intervall(
			$this->attraktionsfaktor,
			AF_MIN,
			AF_MAX,
			AF_STD,
			'fann.attraktionsfaktor'
		);

		$parametrar = explode(',', $this->db_preferenser->hämta_preferens('fann.parametrar'));
		if (count($parametrar) === 3) {
			foreach ($parametrar as $i => $p) {
				$this->limiter[$i] = (float) filter_var($p, FILTER_VALIDATE_FLOAT);
			}

			return;
		}

		$parametrar = $this->limiter;
		$this->db_preferenser->spara_preferens("fann.parametrar", implode(',', $parametrar));
	}
}
