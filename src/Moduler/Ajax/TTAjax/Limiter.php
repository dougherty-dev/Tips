<?php

/**
 * Klass Limiter.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Ajax\TTAjax;

use Tips\Moduler\TT\Konstanter;

/**
 * Klass Limiter.
 */
class Limiter extends Kod {
	use Konstanter;

	/**
	 * Spara limiter för Topptipset.
	 */
	protected function tt_gränser(): void {
		$this->db_preferenser->validera_indata(
			'tt_odds_rätt_min',
			0,
			self::TT_MATCHANTAL,
			self::TT_ODDS_RÄTT_MIN,
			'topptips.odds_rätt_min'
		);

		$this->db_preferenser->validera_indata(
			'tt_odds_rätt_max',
			0,
			self::TT_MATCHANTAL,
			self::TT_ODDS_RÄTT_MAX,
			'topptips.odds_rätt_max'
		);

		$this->db_preferenser->validera_indata(
			'tt_antal_1_min',
			0,
			self::TT_MATCHANTAL,
			self::TT_ANTAL_1_MIN,
			'topptips.antal_1_min'
		);

		$this->db_preferenser->validera_indata(
			'tt_antal_1_max',
			0,
			self::TT_MATCHANTAL,
			self::TT_ANTAL_1_MAX,
			'topptips.antal_1_max'
		);

		$this->db_preferenser->validera_indata(
			'tt_antal_X_min',
			0,
			self::TT_MATCHANTAL,
			self::TT_ANTAL_X_MIN,
			'topptips.antal_X_min'
		);

		$this->db_preferenser->validera_indata(
			'tt_antal_X_max',
			0,
			self::TT_MATCHANTAL,
			self::TT_ANTAL_X_MAX,
			'topptips.antal_X_max'
		);

		$this->db_preferenser->validera_indata(
			'tt_antal_2_min',
			0,
			self::TT_MATCHANTAL,
			self::TT_ANTAL_2_MIN,
			'topptips.antal_2_min'
		);

		$this->db_preferenser->validera_indata(
			'tt_antal_2_max',
			0,
			self::TT_MATCHANTAL,
			self::TT_ANTAL_2_MAX,
			'topptips.antal_2_max'
		);
	}
}
