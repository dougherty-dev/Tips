<?php

/**
 * Klass GridResultat.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Omgang;

use Tips\Klasser\Tips;

/**
 * Klass GridResultat.
 * Resultatgrid med moduldata, vinstrader samt resultatstatistik.
 */
final class GridResultat extends GridResultatTipsresultat {
	/**
	 * Visa resultatgrid.
	 */
	public function visa(): string {
		/**
		 * Skicka tillbaka till Visa.
		 */
		return <<< EOT
					<div class="grid-moduler">
						<h1>📌 Resultat</h1>
						<h2>Moduler</h2>
						<div class="annonser">
{$this->moduler()}						</div>
					</div> <!-- grid-moduler -->
					<div class="grid-vinstrader">
						<p><strong>Vinstrader:</strong></p>
						<div class="tipsrader">
{$this->vinstrader()}						</div>
					</div> <!-- grid-vinstrader -->
					<div class="grid-fördelning">
						<p><strong>{$this->antal_rader} rader spelade</strong></p>
{$this->tipsresultat()}					</div> <!-- grid-fördelning -->
EOT;
	}
}
