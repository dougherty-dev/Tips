<?php

/**
 * Klass Koder.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

use Tips\Moduler\TT\TTKod;
use Tips\Moduler\TT\TTRKod;

/**
 * Klass Koder.
 */
class Koder extends Initiera {
	public TTKod $kod;
	public TTRKod $rkod;

	/**
	 * Fastställ rätt reduceringskoder, samt uppdatera i förekommande fall.
	 */
	protected function hämta_koder(): void {
		$this->kod = TTKod::tryFrom($this->db_preferenser->hämta_preferens('topptips.kod')) ?? TTKod::cases()[0];
		$this->db_preferenser->spara_preferens('topptips.kod', $this->kod->name);

		$this->rkod = TTRKod::tryFrom($this->db_preferenser->hämta_preferens('topptips.rkod')) ?? TTRKod::cases()[0];
		$this->db_preferenser->spara_preferens('topptips.rkod', $this->rkod->name);
	}
}
