<?php

/**
 * Klass Flik.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Investera;

use Tips\Egenskaper\Eka;

/**
 * Klass Flik.
 */
class Flik extends Tabell {
	use Eka;

	/**
	 * Eka ut HTML.
	 * Investerat kapital samt grafer.
	 */
	protected function flik(string $tabell, int $netto, int $acknetto): void {
		/**
		 * Returnera till Tabell.
		 */
		echo <<< EOT
			<div id="flikar-investera">
				<div class="generell-övre-grid">
					<div class="generell-övre">
						<h1>Invest</h1>
						<p>{$this->antal_investeringar} investeringar | Investerat: {$this->ackumulerad_utgift} | Vinst: {$this->ackumulerad_vinst} | Netto: $netto</p>
						<p>Ackumulerat maximum: {$this->ackmax} | Ackumulerat minimum: {$this->ackmin} | Differens: $acknetto</p>
						<p>Visa antal investeringar: <input class="nummer" type="number" min="5" autocomplete="off" id="investera_visa_antal" value="{$this->visa_antal}"></p>
$tabell						<p>{$this->visa_antal} / {$this->antal_investeringar} senaste investeringar:</p>
{$this->tabell}
					</div> <!-- generell-övre -->
				</div> <!-- generell-övre-grid -->
				<div class="generell-nedre-grid">
					<div class="generell-nedre">
						<p>{$this->eka($this->graf->rendera_tipsgraf($this->fil))}</p>
						<p>{$this->eka($this->ackumulerad_graf->rendera_tipsgraf($this->fil_ack))}</p>
					</div> <!-- generell-nedre -->
				</div> <!-- generell-nedre-grid -->
			</div> <!-- flikar-invest -->

EOT;
	}
}
