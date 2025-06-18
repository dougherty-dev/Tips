<?php

/**
 * Klass Preferenser.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

use Tips\Moduler\TT\Konstanter;
use Tips\Moduler\TT\TTKod;
use Tips\Moduler\TT\TTRKod;

/**
 * Klass Preferenser.
 * Kontrollera inställningar.
 */
class Preferenser extends Koder {
	use Konstanter;

	public bool $tt_pröva_spikar;
	public bool $tt_pröva_täckning;
	public bool $tt_pröva_t_intv;
	public bool $tt_pröva_o_intv;
	public bool $tt_pröva_reduktion;

	public int $odds_rätt_min = self::TT_ODDS_RÄTT_MIN;
	public int $odds_rätt_max = self::TT_ODDS_RÄTT_MAX;
	public int $antal_1_min = self::TT_ANTAL_1_MIN;
	public int $antal_1_max = self::TT_ANTAL_1_MAX;
	public int $antal_X_min = self::TT_ANTAL_X_MIN;
	public int $antal_X_max = self::TT_ANTAL_X_MAX;
	public int $antal_2_min = self::TT_ANTAL_2_MIN;
	public int $antal_2_max = self::TT_ANTAL_2_MAX;

	public int $visa_antal_bokf = 5;
	public string $strategi = '';

	/**
	 * Uppdatera preferenser.
	 */
	protected function uppdatera_preferenser(): void {
		$this-> hämta_koder();

		/**
		 * Min odds rätt inom intervall.
		 */
		$this->db_preferenser->int_preferens_i_intervall(
			$this->odds_rätt_min,
			0,
			self::TT_MATCHANTAL,
			self::TT_ODDS_RÄTT_MIN,
			'topptips.odds_rätt_min'
		);

		/**
		 * Max odds rätt inom intervall.
		 */
		$this->db_preferenser->int_preferens_i_intervall(
			$this->odds_rätt_max,
			0,
			self::TT_MATCHANTAL,
			self::TT_ODDS_RÄTT_MAX,
			'topptips.odds_rätt_max'
		);

		/**
		 * Antal tecken inom intervall.
		 */
		$this->db_preferenser->int_preferens_i_intervall(
			$this->antal_1_min,
			0,
			self::TT_MATCHANTAL,
			self::TT_ANTAL_1_MIN,
			'topptips.antal_1_min'
		);

		$this->db_preferenser->int_preferens_i_intervall(
			$this->antal_1_max,
			0,
			self::TT_MATCHANTAL,
			self::TT_ANTAL_1_MAX,
			'topptips.antal_1_max'
		);

		$this->db_preferenser->int_preferens_i_intervall(
			$this->antal_X_min,
			0,
			self::TT_MATCHANTAL,
			self::TT_ANTAL_X_MIN,
			'topptips.antal_X_min'
		);

		$this->db_preferenser->int_preferens_i_intervall(
			$this->antal_X_max,
			0,
			self::TT_MATCHANTAL,
			self::TT_ANTAL_X_MAX,
			'topptips.antal_X_max'
		);

		$this->db_preferenser->int_preferens_i_intervall(
			$this->antal_2_min,
			0,
			self::TT_MATCHANTAL,
			self::TT_ANTAL_2_MIN,
			'topptips.antal_2_min'
		);

		$this->db_preferenser->int_preferens_i_intervall(
			$this->antal_2_max,
			0,
			self::TT_MATCHANTAL,
			self::TT_ANTAL_2_MAX,
			'topptips.antal_2_max'
		);

		/**
		 * Hämta inställningar.
		 */
		$this->tt_pröva_spikar = (bool) $this->db_preferenser->hämta_preferens('topptips.tt_pröva_spikar');
		$this->tt_pröva_täckning = (bool) $this->db_preferenser->hämta_preferens('topptips.tt_pröva_täckning');
		$this->tt_pröva_reduktion = (bool) $this->db_preferenser->hämta_preferens('topptips.tt_pröva_reduktion');
		$this->tt_pröva_t_intv = (bool) $this->db_preferenser->hämta_preferens('topptips.tt_pröva_t_intv');
		$this->tt_pröva_o_intv = (bool) $this->db_preferenser->hämta_preferens('topptips.tt_pröva_o_intv');
		$this->strategi = $this->db_preferenser->hämta_preferens('topptips.strategi');
		$this->visa_antal_bokf = (int) $this->db_preferenser->hämta_preferens('topptips.visa_antal_bokf');
	}
}
