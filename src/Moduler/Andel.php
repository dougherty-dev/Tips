<?php

/**
 * Klass Andel.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler;

use ReflectionClass;
use Tips\Klasser\Utdelning;
use Tips\Klasser\Prediktioner;
use Tips\Klasser\Matcher;
use Tips\Moduler\Andel\Andelar;

/**
 * Klass Andel.
 * Filtrerar givna teckenandelar i intervall.
 */
final class Andel extends Andelar {
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
	}

	/**
	 * Visa modul.
	 */
	public function visa_modul(): void {
		$klass = (new ReflectionClass($this))->getShortName();
		echo <<< EOT
			<div id="modulflikar-$klass">
				<p>1: 3–8 | X: 1–6 | 2: 2–7 (86%)</p>
				<div>
					<p>1: <input type="number" size="2" min=0 max=13 autocomplete="off" id="andel_1_min" value="{$this->andel_1_min}">–
					<input type="number" size="2" min=0 max=13 autocomplete="off" id="andel_1_max" value="{$this->andel_1_max}"> |
					X: <input type="number" size="2" min=0 max=13 autocomplete="off" id="andel_x_min" value="{$this->andel_x_min}">–
					<input type="number" size="2" min=0 max=13 autocomplete="off" id="andel_x_max" value="{$this->andel_x_max}"> |
					2: <input type="number" size="2" min=0 max=13 autocomplete="off" id="andel_2_min" value="{$this->andel_2_min}">–
					<input type="number" size="2" min=0 max=13 autocomplete="off" id="andel_2_max" value="{$this->andel_2_max}"></p>
				</div>
				<div class="andel-kolumner">
{$this->andelar()}				</div>
			</div> <!-- modulflikar-$klass -->

EOT;
	}
}
