<?php

/**
 * Klass Visa.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Distribution;

use Tips\Egenskaper\Eka;

/**
 * Klass Visa.
 * HTML-mall med erforderliga variabler.
 * Visar graf över distribution samt allmän fördelning.
 * Formulär med fält för värden samt genvägar.
 */
class Visa extends Prova {
	use Eka;

	/**
	 * Eka ut modulsida.
	 */
	protected function visa(string $klass, string $oddssumma, string $distgraf): void {
		/**
		 * Flik definieras med gridsystem.
		 * Parametrar i övre vänster grid, historik i högra.
		 * Grafer i nedre grid.
		 */
		echo <<< EOT
			<div id="modulflikar-{$klass}">
				<div class="distribution-grid">
					<div class="grid-intervall">
						<h1>{$this->eka($this->utdelning->har_tipsrad && $this->pröva_tipsrad($this->utdelning->tipsrad_012) ? '✅' : '❌')} $klass</h1>
						<p><strong>Distribution av radsumma för odds</strong></p>
						<p>Oddssumma: $oddssumma<br>
							Andel utfall: {$this->procentandel} %<br>
							Andelssumma: {$this->andelssumma} rader</p>
{$this->värden()}{$this->genvägar()}
					</div> <!-- grid-intervall -->
					<div class="grid-förekomst">
{$this->historik}					</div> <!-- grid-förekomst -->
					<div class="grid-distribution">
						<div>
							<p>$distgraf</p>
						</div>
					</div> <!-- grid-distribution -->
				</div> <!-- distribution-grid -->
			</div> <!-- modulflikar-$klass -->

EOT;
	}

	/**
	 * Eka ut värden.
	 * Fält för val av aktuell distribution samt grunddistribution.
	 */
	private function värden(): string {
		return <<< EOT
						<p>Aktuell distribution: <input class="nummer" type="number" min="0" autocomplete="off" id="distribution_minprocent" value="{$this->minprocent}"> – <input class="nummer" type="number" min="0" autocomplete="off" id="distribution_maxprocent" value="{$this->maxprocent}"> % ({$this->minsumma} – {$this->maxsumma}) <button id="grunddistribution">⇦ Grund</button></p>
						<p>Grundvärden: <input class="nummer" type="number" min="0" autocomplete="off" id="grunddistribution_minprocent" value="{$this->grund_minprocent}"> – <input class="nummer" type="number" min="0" autocomplete="off" id="grunddistribution_maxprocent" value="{$this->grund_maxprocent}"> %</p>
EOT;
	}

	/**
	 * Eka ut genvägar.
	 * Knappar för snabbval av intervall för distribution.
	 */
	private function genvägar(): string {
		return '
						<p><button class="distribution_schema" value="[0.1, 2]">0.1–2 (8–35&nbsp;%)</button>
							<button class="distribution_schema" value="[0.1, 3]">0.1–3 (8–41&nbsp;%)</button>
							<button class="distribution_schema" value="[0.1, 5]">0.1–5 (8–51&nbsp;%)</button>
							<button class="distribution_schema" value="[0.1, 10]">0.1–10 (8–65&nbsp;%)</button></p>';
	}
}
