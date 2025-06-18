<?php

/**
 * Klass Kluster.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler;

use ReflectionClass;
use Tips\Klasser\Tips;
use Tips\Klasser\Utdelning;
use Tips\Klasser\Prediktioner;
use Tips\Klasser\Matcher;
use Tips\Egenskaper\Eka;
use Tips\Moduler\Kluster\Prova;

/**
 * Klass Kluster.
 * Finn areor i projicerad tipsgraf där vinster tenderar att klustras.
 * Beror i sin tur på att vissa teckenandelar är vanligare än vad statistiken medger.
 * Har parametrar för proximitet och storlek på kluster.
 */
final class Kluster extends Prova {
	use Eka;

	/**
	 * Initiera.
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

		$andel = ($this->antal_rader > 0) ? round(100 * $this->klustrade_rader / $this->antal_rader) : 0;
		$andel_rader = round(100 * $this->area / pow(3, MATCHANTAL), 1); // andel rader i kluster
		$tipsgraf = $this->graf->rendera_tipsgraf($this->kombinationsgraf);

		/**
		 * Eka ut modulflik.
		 */
		echo <<< EOT
			<div id="modulflikar-$klass">
				<div class="kluster-övre-grid">
					<div class="kluster-grid-omgång">
						<h1>{$this->eka($this->utdelning->har_tipsrad && $this->pröva_tipsrad($this->utdelning->tipsrad_012) ? '✅' : '❌')} $klass</h1>
						<p>Släpp igenom rader i klustrade områden.</p>
						<p>Min antal per kluster: <input class="nummer" type="number" min="1" autocomplete="off" id="kluster_min_antal_per_kluster" value="{$this->min_antal}"></p>
						<p>Min radie: <input class="nummer" type="number" min="1" max="300" autocomplete="off" id="kluster_min_radie" value="{$this->min_radie}"></p>
						<p>Klustrad area ≈ {$this->area} ($andel_rader&nbsp;%)</p>
						<p>Klustrade rader: {$this->klustrade_rader} / {$this->antal_rader} ($andel&nbsp;%)</p>
					</div> <!-- kluster-grid-omgång -->
				</div> <!-- kluster-övre-grid -->
				<div class="kluster-nedre-grid">
					<div class="kluster-grid-graf">
						<p id="klustergraf">$tipsgraf</p>
					</div> <!-- kluster-grid-graf -->
				</div> <!-- kluster-nedre-grid -->
			</div> <!-- modulflikar-$klass -->

EOT;
	}

	/**
	 * Finn kluster då omgång sparas generellt.
	 * @SuppressWarnings("PHPMD.UnusedFormalParameter")
	 */
	public function spara_omgång(Tips $tips): void {
		$this->finn_kluster();
	}
}
