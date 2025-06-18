<?php

/**
 * Klass Visa.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Spelade;

use Tips\Klasser\Vinstspridning;

/**
 * Klass Visa.
 * Rendera spelade rader med grafer och information.
 * Tom graf visas då inga genererade rader finns.
 */
class Mall extends Rader {
	/**
	 * Eka ut modul i relevant flik.
	 */
	protected function markup(string $radknapp, string $kombinationsgraf): void {
		/**
		 * Rendera graf med vinstspridning.
		 */
		$produkt = ucfirst($this->utdelning->spel->speltyp->produktnamn());
		$vinstspridningsgraf = (new Vinstspridning($this->utdelning->tipsrad_012))->visa_vinstspridning();

		/**
		 * Andel valda rader har bara relevans då genererade rader finns.
		 */
		$andel = match ($this->antal_genererade > 0) {
			true => number_format(100 * $this->antal_utvalda_rader / $this->antal_genererade, 2),
			false => ''
		};

		/**
		 * HTML-mall för modulen.
		 * Definiera även en slumpad snabbrad från utvalda rader.
		 */
		echo <<< EOT
			<div id="flikar-spelat">
				<div class="spelade-grid">
					<div class="grid-spelade-rader">
						<h1>Spelade</h1>
						<p>$produkt {$this->utdelning->spel->omgång}</p>
						<div class="tipsrader" style="padding-right: 2em;">
{$this->snabbrad()}
$radknapp{$this->spelade_rader()}
						</div>	<!-- tipsrader -->
					</div>	<!-- grid-spelade-rader -->
					<div class="grid-graf">
						<p><strong>Spelade rader</strong>: {$this->antal_utvalda_rader} / {$this->antal_genererade} rader ($andel %)<br>
							<span style="color: #0ff;">13 rätt</span>
							<span style="color: #f0f;">12 rätt</span>
							<span style="color: #fff;">11 rätt</span>
							<span style="color: #0f0;">10 rätt</span>
						</p>
						<div>
							<p>{$this->tipsgraf}</p>
							<p>$vinstspridningsgraf</p>
							<p>$kombinationsgraf</p>
						</div>
					</div>	<!-- grid-graf -->
				</div> <!-- spelade-grid -->
			</div> <!-- flikar-spelat -->

EOT;
	}
}
