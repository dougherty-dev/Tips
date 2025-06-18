<?php

/**
 * Klass Rader.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Ajax\TTAjax;

use Tips\Moduler\TT\Konstanter;

/**
 * Klass Rader.
 */
class Rader extends Spikar {
	use Konstanter;

	/**
	 * Spara antal rader för Topptipset.
	 */
	protected function tt_antal_rader(): void {
		$this->db_preferenser->validera_indata('tt_antal_rader', self::TT_MIN_RADER, self::TT_MAX_RADER, self::TT_MIN_DEFAULT, 'topptips.antal_rader');
	}

	/**
	 * Spara typ för Topptipset.
	 */
	protected function topptipstyp(): void {
		$this->db_preferenser->spara_preferens("topptips.typ", (string) filter_var($_REQUEST['topptipstyp'], FILTER_SANITIZE_SPECIAL_CHARS));
	}

	/**
	 * Spara status för Topptipset.
	 */
	protected function tt_status(): void {
		$status = filter_var($_REQUEST['tt_status'], FILTER_VALIDATE_BOOLEAN);
		$id = filter_var($_REQUEST['tt_id'], FILTER_SANITIZE_SPECIAL_CHARS);
		$this->db_preferenser->spara_preferens("topptips." . $id, strval($status));
	}
}
