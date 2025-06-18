<?php

/**
 * Klass Vinstgraf.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler;

use ReflectionClass;
use Tips\Moduler\Vinstgraf\Tipsrader;
use Tips\Egenskaper\Eka;

/**
 * Klass Vinstgraf.
 */
final class Vinstgraf extends Tipsrader {
	use Eka;

	/**
	 * Visa vinstgraf i modulflik.
	 */
	public function visa_modul(): void {
		$klass = (new ReflectionClass($this))->getShortName();

		$this->hämta_tipsrader();
		$this->antal_rader = count($this->tipsrader);
		$andel = ($this->totalt_antal_rader > 0) ? round(100 * $this->antal_rader / $this->totalt_antal_rader, 1) : 0;

		/**
		 * Rendera vinstgraf.
		 */
		foreach ($this->tipsrader as [$tipsrad_012, $u13]) {
			$this->rita($tipsrad_012, $u13);
		}

		$this->graf->spara_tipsgraf(self::FIL);

		/**
		 * Eka ut modul i HTML.
		 */
		echo <<< EOT
			<div id="modulflikar-$klass">
				<h1>$klass</h1>
				<p>{$this->eka($this->graf->rendera_tipsgraf(self::FIL))}</p>
				<p>{$this->antal_rader} / {$this->totalt_antal_rader} rader ($andel %)</p>
				<form>
					<p>[min, max]: utd 13r: <input type="number" min="100" max="1000000" step="10000" id="utdelning_13_min" size="8" value="{$this->utdelning_13_min}">
					<input type="number" min="10000" max="20000000" step="10000" id="utdelning_13_max" size="9" value="{$this->utdelning_13_max}">
					<input type="submit" value="Uppdatera värden" id="uppdatera_vinstgraf"></p>
				</form>
			</div> <!-- modulflikar-$klass -->

EOT;
	}
}
