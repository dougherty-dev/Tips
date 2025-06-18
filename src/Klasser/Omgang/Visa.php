<?php

/**
 * Klass Visa.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Omgang;

use Tips\Klasser\Tips;

/**
 * Klass Visa.
 */
final class Visa {
	/**
	 * Initiera.
	 */
	public function __construct(private Tips $tips) {
		$this->visa_omgångsdata();
	}

	/**
	 * Visa omgångsdata.
	 * Fördela över ett flertal klasser.
	 */
	public function visa_omgångsdata(): void {
		/**
		 * Omgångsdata
		 */
		$grid_omgång = new GridOmgang($this->tips);
		/**
		 * Element för generering av tipsrader
		 */
		$grid_generera = new GridGenerera($this->tips);
		/**
		 * Grid för rader
		 */
		$grid_rader = new GridRader($this->tips);
		/**
		 * Omgångsdata
		 */
		$grid_omgångsdata = new GridOmgangsdata($this->tips);
		/**
		 * Grid för favoriter
		 */
		$grid_favoriter = new GridFavoriter($this->tips);
		/**
		 * Tabeller för matcher
		 */
		$grid_matcher = new GridMatcher($this->tips);
		/**
		 * Visa resultat
		 */
		$grid_resultat = new GridResultat($this->tips);
		echo <<< EOT
			<div id="flikar-omg">
				<div class="övre-grid">
					<div class="grid-omgång">
{$grid_omgång->visa()}
					</div> <!-- grid-omgång -->
					<div class="grid-generera">
{$grid_generera->visa()}
					</div> <!-- grid-generera -->
					<div class="grid-rader">
{$grid_rader->visa()}
					</div> <!-- grid-rader -->
				</div> <!-- övre-grid -->
				<form id="matchdata" method="post" action="/">
					<div class="mellersta-grid">
						<div class="grid-omgångsdata">
{$grid_omgångsdata->visa()}
						</div> <!-- grid-omgångsdata -->
						<div class="grid-favoriter">
{$grid_favoriter->visa()}
						</div> <!-- grid-favoriter -->
						<div class="grid-matcher">
{$grid_matcher->visa()}
						</div> <!-- grid-matcher -->
					</div> <!-- mellersta-grid -->
				</form> <!-- matchdata -->
				<div class="undre-grid">
{$grid_resultat->visa()}
				</div> <!-- undre-grid -->
			</div> <!-- flikar-omg -->
			<div id="flikar-logg">
				<div class="generell-övre-grid">
					<div class="generell-övre">
						<h1>Logg</h1>
{$this->tips->spel->db->logg->hämta_logg()}
					</div> <!-- generell-övre -->
				</div> <!-- generell-övre-grid -->
			</div> <!-- flikar-logg -->

EOT;
	}
}
