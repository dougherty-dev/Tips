<?php

/**
 * Klass Preferenser.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Andel;

use Tips\Klasser\Utdelning;
use Tips\Klasser\Prediktioner;
use Tips\Klasser\Matcher;
use Tips\Klasser\DBPreferenser;
use Tips\Egenskaper\Tick;

/**
 * Klass Preferenser.
 * Kontrollera inställningar.
 */
class Preferenser {
	use Tick;

	protected DBPreferenser $db_preferenser;
	protected int $andel_1_min = 0;
	protected int $andel_1_max = 0;
	protected int $andel_x_min = 0;
	protected int $andel_x_max = 0;
	protected int $andel_2_min = 0;
	protected int $andel_2_max = 0;

	/**
	 * Initiera.
	 */
	public function __construct(
		protected Utdelning $utdelning,
		protected Prediktioner $odds,
		protected Prediktioner $streck,
		protected Matcher $matcher
	) {
		$this->db_preferenser = new DBPreferenser($this->utdelning->spel->db);
	}

	/**
	 * Uppdatera preferenser.
	 */
	protected function uppdatera_preferenser(): void {
		/**
		 * Attraktionsfaktor mellan 1 och 3^13.
		 */
		$this->db_preferenser->int_preferens_i_intervall(
			$this->attraktionsfaktor,
			AF_MIN,
			AF_MAX,
			AF_STD,
			'andel.attraktionsfaktor'
		);

		/**
		 * Andel tecken mellan 0 och 13.
		 */
		$this->db_preferenser->int_preferens_i_intervall(
			$this->andel_1_min,
			0,
			13,
			3,
			'andel.andel_1_min'
		);

		$this->db_preferenser->int_preferens_i_intervall(
			$this->andel_1_max,
			0,
			13,
			8,
			'andel.andel_1_max'
		);

		$this->db_preferenser->int_preferens_i_intervall(
			$this->andel_x_min,
			0,
			13,
			1,
			'andel.andel_x_min'
		);

		$this->db_preferenser->int_preferens_i_intervall(
			$this->andel_x_max,
			0,
			13,
			6,
			'andel.andel_x_max'
		);

		$this->db_preferenser->int_preferens_i_intervall(
			$this->andel_2_min,
			0,
			13,
			2,
			'andel.andel_2_min'
		);

		$this->db_preferenser->int_preferens_i_intervall(
			$this->andel_2_max,
			0,
			13,
			7,
			'andel.andel_2_max'
		);

		/**
		 * Jämför andelar.
		 */
		$this->db_preferenser->int_komparera_preferenser(
			$this->andel_1_min,
			$this->andel_1_max,
			3,
			8,
			'andel.andel_1_min',
			'andel.andel_1_max'
		);

		$this->db_preferenser->int_komparera_preferenser(
			$this->andel_x_min,
			$this->andel_x_max,
			1,
			6,
			'andel.andel_x_min',
			'andel.andel_x_max'
		);

		$this->db_preferenser->int_komparera_preferenser(
			$this->andel_2_min,
			$this->andel_2_max,
			2,
			7,
			'andel.andel_2_min',
			'andel.andel_2_max'
		);
	}
}
