<?php

/**
 * Klass Visa.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Investera;

use Tips\Klasser\Graf;

/**
 * Klass Visa.
 * Visar n senaste lagda spelen.
 */
class Visa extends Flik {
	/**
	 * Visa investeringar.
	 */
	public function visa_investering(): void {
		$this->graf = new Graf();
		$this->ackumulerad_graf = new Graf();
		$this->uppdatera_preferenser();
		$this->hämta_investering();
		$this->hämta_investeringar();

		/**
		 * Antal genererade rader.
		 */
		$andel = match (true) {
			$this->antal_genererade > 0 =>
				number_format(100 * $this->antal_utvalda_rader / $this->antal_genererade, 2),
			default => 0
		};

		/**
		 * Stil för vinstrader.
		 */
		[$s13, $s12, $s11, $s10] = $this->vinststil($this->vinstdata);

		$tabell = match ($this->spelad) {
			true => $this->tabell($andel, $s13, $s12, $s11, $s10),
			false => ''
		};

		$netto = $this->ackumulerad_vinst - $this->ackumulerad_utgift;
		$acknetto = $this->ackmax - $this->ackmin;

		/**
		 * Plotta grafer?
		 */
		if (!file_exists(GRAF . $this->fil)) {
			$this->plotta_investeringar();
		}

		if (!file_exists(GRAF . $this->fil_ack)) {
			$this->plotta_ackumulerad_investering();
		}

		$this->flik($tabell, $netto, $acknetto);
	}
}
