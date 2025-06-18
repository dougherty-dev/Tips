<?php

/**
 * Klass Visa.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\FANN;

use ReflectionClass;
use Tips\Egenskaper\Eka;

/**
 * Klass Visa.
 */
class Visa extends Parametrar {
	use Eka;

	/**
	 * Visa modul.
	 * Visa sida för modul med relevanta data.
	 * Rendera i gridsystem.
	 */
	public function visa_modul(): void {
		$klass = (new ReflectionClass($this))->getShortName();

		echo <<< EOT
			<div id="modulflikar-$klass">
				<div class="FANN-övre-grid">
					<div class="FANN-grid-fannomgång">
						<h1>{$this->eka($this->utdelning->har_tipsrad && $this->pröva_tipsrad($this->utdelning->tipsrad_012) ? '✅' : '❌')} $klass</h1>
{$this->fannomgång()}
					</div> <!-- FANN-grid-fannomgång -->
				</div> <!-- FANN-övre-grid -->
				<div class="FANN-nedre-grid">
					<div class="FANN-grid-fanndata">
{$this->fanndata()}
					</div> <!-- FANN-grid-fanndata -->
					<div class="FANN-grid-fannparametrar">
{$this->fannparametrar()}
					</div> <!-- FANN-grid-fannparametrar -->
					<div class="FANN-grid-fannrätt">
{$this->db_preferenser->hämta_preferens('fann.fördelning')}					</div> <!-- FANN-grid-fannrätt -->
				</div> <!-- FANN-nedre-grid -->
			</div> <!-- modulflikar-$klass -->

EOT;
	}
}
