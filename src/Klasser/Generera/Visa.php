<?php

/**
 * Klass Visa.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Generera;

/**
 * Klass Visa.
 */
class Visa extends Spara {
	/**
	 * Visa genererade rader samt graf.
	 */
	protected function visa(string $spelade_rader): void {
		/**
		 * Rendera tipsgrafer.
		 */
		$tipsgraf = $this->graf->rendera_tipsgraf($this->bildfil);
		$kombinationsgraf = $this->tips->spelade->kombinationsgraf($this->kombinerad_bildfil, $this->bildfil);

		/**
		 * Procent spelade rader gentemot genererade.
		 */
		$kvot = ($this->antal_genererade > 0) ? number_format(100 * $this->antal_utvalda_rader / $this->antal_genererade, 2) : 0;

		/**
		 * Visa resursförbrukning av tid och minne.
		 */
		$minne = round(memory_get_peak_usage() / 1048576, 2);
		$tidsdifferens = round((hrtime(true) - $this->tid) / 1000000000, 2);
		$maxtid = ($tidsdifferens < MAXTID) ? "✅ " : "❌ ";
		$maxtid .= strval(MAXTID) . ' s';

		/**
		 * Eka ut flik med genererat innehåll.
		 */
		echo <<< EOT
			<div id="flikar-genererat">
				<div class="spelade-grid">
					<div class="grid-spelade-rader">
						<h1>Genererade</h1>
						<p><strong>Omgång {$this->tips->spel->omgång}</strong></p>
						<div class="tipsrader">
							<p>{$this->tips->spel->speltyp->produktnamn()}, ({$this->antal_utvalda_rader} rader)</p>
$spelade_rader							…
						</div> <!-- tipsrader -->
					</div> <!-- grid-spelade-rader -->
					<div class="grid-graf">
						<p>Rader: {$this->antal_utvalda_rader} ({$this->antal_genererade}: $kvot %) ($tidsdifferens s | $maxtid, $minne MB)</p>
						<p>$tipsgraf</p>
						<p>$kombinationsgraf</p>
						<p><input type="submit" id="spara_generering" value="Spara">
							<label>Investera<input type="checkbox" checked id="investera_sparad"></label></p>
					</div> <!-- grid-graf -->
				</div> <!-- spelade-grid -->
			</div> <!-- spelade-genererat -->

EOT;
	}
}
