<?php

/**
 * Klass Visa.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

use ReflectionClass;
use Tips\Moduler\TT;

/**
 * Klass Visa.
 * Flytta komplexitet till en egen klass.
 */
final class Visa {
	/**
	 * Initiera.
	 */
	public function __construct(public TT $tt) {
	}

	/**
	 * Visa modul.
	 */
	public function visa(): void {
		$klass = (new ReflectionClass($this->tt))->getShortName();

		/**
		 * Hämta enskilda delar av modulflikens utdata som klassinstanser.
		 */
		$tt_grid_omgång = new TTGridOmgang($this->tt); // omgångsdata
		$tt_grid_bokföring = new TTGridBokforing($this->tt); // bokföringsgrid
		$tt_grid_preferenser = new TTGridPreferenser($this->tt); // preferenser
		$tt_grid_matcher = new TTGridMatcher($this->tt); // tabeller med matcher
		$tt_grid_koddata = new TTGridKoddata($this->tt); // information om nyttjade koder
		$tt_grid_rader = new TTGridRader($this->tt); // spelade rader med mera

		/**
		 * Eka ut HTML.
		 */
		echo <<< EOT
			<div id="modulflikar-$klass">
				<div class="tt-övre-grid">
					<div class="tt-grid-omgång">
{$tt_grid_omgång->tt_grid_omgång()}
					</div> <!-- tt-grid-omgång -->
					<div class="tt-grid-bokföring">
{$tt_grid_bokföring->tt_grid_bokföring()}
					</div> <!-- tt-grid-bokföring -->
				</div> <!-- tt-övre-grid -->
				<div class="tt-mellersta-grid">
					<div class="tt-grid-preferenser">
{$tt_grid_preferenser->tt_grid_preferenser()}
					</div> <!-- tt-grid-preferenser -->
					<div class="tt-grid-matcher">
{$tt_grid_matcher->tt_grid_matcher()}
					</div> <!-- tt-grid-matcher -->
					<div class="tt-grid-koddata">
{$tt_grid_koddata->tt_grid_koddata()}
					</div> <!-- tt-grid-koddata -->
				</div> <!-- tt-mellersta-grid -->
				<div class="tt-undre-grid">
					<div class="tt-grid-rader">
{$tt_grid_rader->tt_grid_rader()}
{$tt_grid_rader->tt_grid_statistik()}
					</div> <!-- tt-grid-rader -->
				</div> <!-- tt-undre-grid -->
			</div> <!-- modulflikar-$klass -->

EOT;
	}
}
