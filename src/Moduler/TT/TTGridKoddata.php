<?php

/**
 * Klass TTGridKoddata.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

use Tips\Moduler\TT;

/**
 * Klass TTGridKoddata.
 */
final class TTGridKoddata {
	public function __construct(private TT $tt) {
	}

	/**
	 * Visa allmänna data för aktuell kod.
	 */
	public function tt_grid_koddata(): string {
		return <<< EOT
						<h1>Kodanalys</h1>
						<div id="tt_rkodanalys">
{$this->tt->rkod->koddata()}
						</div> <!-- tt_rkodanalys -->
						<div id="tt_kodanalys">
{$this->tt->kod->koddata()}
						</div> <!-- tt_kodanalys -->
EOT;
	}
}
