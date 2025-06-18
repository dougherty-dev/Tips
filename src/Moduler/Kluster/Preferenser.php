<?php

/**
 * Klass Preferenser.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Kluster;

use Tips\Egenskaper\Tick;

/**
 * Klass Preferenser.
 */
class Preferenser extends Finn {
	use Tick;
	use Konstanter;

	/**
	 * Uppdatera preferenser.
	 */
	protected function uppdatera_preferenser(): void {
		$this->db_preferenser->int_preferens_i_intervall(
			$this->attraktionsfaktor,
			AF_MIN,
			AF_MAX,
			AF_STD,
			'kluster.attraktionsfaktor'
		);

		$this->db_preferenser->int_preferens_i_intervall(
			$this->min_antal,
			self::KLUSTER_MIN_ANTAL_MIN,
			self::KLUSTER_MIN_ANTAL_MAX,
			self::KLUSTER_MIN_ANTAL_STD,
			'kluster.min_antal'
		);

		$this->db_preferenser->int_preferens_i_intervall(
			$this->min_radie,
			self::KLUSTER_MIN_RADIE_MIN,
			self::KLUSTER_MIN_RADIE_MAX,
			self::KLUSTER_MIN_RADIE_STD,
			'kluster.min_radie'
		);

		$this->area = (int) $this->db_preferenser->hämta_preferens('kluster.area');
		$this->antal_rader = (int) $this->db_preferenser->hämta_preferens('kluster.antal_rader');
		$this->klustrade_rader = (int) $this->db_preferenser->hämta_preferens('kluster.klustrade_rader');

		$rektanglar = $this->db_preferenser->hämta_preferens('kluster.rektanglar');
		$this->rektanglar = explodera_koordinater($rektanglar, 4, 'intval');

		if ($this->rektanglar === []) {
			$this->finn_kluster();
		}
	}
}
