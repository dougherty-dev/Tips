<?php

/**
 * Klass Preferenser.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\HG;

use Tips\Klasser\Utdelning;
use Tips\Klasser\Prediktioner;
use Tips\Klasser\Matcher;
use Tips\Klasser\DBPreferenser;
use Tips\Egenskaper\Tick;
use Tips\Moduler\HG\Konstanter;

/**
 * Klass Preferenser.
 */
class Preferenser {
	use Tick;
	use Konstanter;

	public DBPreferenser $db_preferenser;
	public int $hg_min = self::HG_MIN;
	/**
	 * @var string[] $utdata
	 */
	public array $utdata = [];

	public function __construct(
		protected Utdelning $utdelning,
		protected Prediktioner $odds,
		protected Prediktioner $streck,
		protected Matcher $matcher
	) {
	}

	/**
	 * Uppdatera preferenser.
	 */
	protected function uppdatera_preferenser(): void {
		$this->db_preferenser->int_preferens_i_intervall(
			$this->attraktionsfaktor,
			AF_MIN,
			AF_MAX,
			AF_STD,
			'hg.attraktionsfaktor'
		);

		$this->db_preferenser->int_preferens_i_intervall(
			$this->hg_min,
			self::HG_MIN,
			self::HG_MAX,
			self::HG_STD,
			'hg.hg_min'
		);

		$this->db_preferenser->hämta_preferens('hg.hg_min');
	}
}
