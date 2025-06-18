<?php

/**
 * Klass System.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler;

use ReflectionClass;
use Tips\Klasser\Utdelning;
use Tips\Klasser\Prediktioner;
use Tips\Klasser\Matcher;
use Tips\Moduler\System\GridPreferenser;
use Tips\Egenskaper\Eka;

/**
 * Klass System.
 * För reducerade system och manuella garderingar.
 */
final class System extends GridPreferenser {
	use Eka;

	/**
	 * Initiera. Uppdatera konstruktor.
	 */
	public function __construct(
		protected Utdelning $utdelning,
		protected Prediktioner $odds,
		protected Prediktioner $streck,
		protected Matcher $matcher
	) {
		parent::__construct($utdelning, $odds, $streck, $matcher);
		$this->uppdatera_preferenser();
		$this->hämta_system();
		$this->beräkna_reducerad_kod();
	}

	/**
	 * Visa modul.
	 */
	public function visa_modul(): void {
		$klass = (new ReflectionClass($this))->getShortName();

		echo <<< EOT
			<div id="modulflikar-$klass">
				<div class="system-övre-grid">
					<div class="system-grid-preferenser">
						<h1>{$this->eka($this->utdelning->har_tipsrad && $this->pröva_tipsrad($this->utdelning->tipsrad_012) ? '✅' : '❌')} $klass</h1>
{$this->grid_preferenser()}
					</div> <!-- system-grid-preferenser -->
					<div class="system-grid-koddata">
{$this->grid_koddata()}
					</div> <!-- system-grid-koddata -->
					<div class="system-grid-garderingar">
{$this->grid_garderingar()}
					</div> <!-- system-grid-garderingar -->
				</div> <!-- system-övre-grid -->
			</div> <!-- modulflikar-$klass -->

EOT;
	}
}
