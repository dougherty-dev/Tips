<?php

/**
 * Klass Kod.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Ajax\TTAjax;

use Tips\Moduler\TT\TTKod;
use Tips\Moduler\TT\TTRKod;

/**
 * Klass Kod.
 */
class Kod extends Rader {
	/**
	 * Spara aktuell kod för Topptipset.
	 */
	protected function tt_kod(): void {
		$kod = TTKod::tryFrom((string) filter_var($_REQUEST['tt_kod'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		if (!($kod instanceof TTKod)) {
			$kod = TTKod::cases()[0];
		}

		$this->db_preferenser->spara_preferens('topptips.kod', $kod->value);
		echo <<< EOT
{$kod->koddata()}
EOT;
	}

	/**
	 * Spara aktuell R-kod för Topptipset.
	 */
	protected function tt_rkod(): void {
		$rkod = TTRKod::tryFrom((string) filter_var($_REQUEST['tt_rkod'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		if (!($rkod instanceof TTRKod)) {
			$rkod = TTRKod::cases()[0];
		}

		$this->db_preferenser->spara_preferens('topptips.rkod', $rkod->value);
		echo <<< EOT
{$rkod->koddata()}
EOT;
	}
}
