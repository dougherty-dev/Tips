<?php

/**
 * Klass PlottaAck.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Investera;

use Tips\Klasser\Graf;

/**
 * Klass PlottaAck.
 * Graf för ackumulerat netto över tid.
 */
class PlottaAck extends Plotta {
	protected Graf $ackumulerad_graf;
	protected string $fil_ack = '/ack_investeringsgraf.png';

	/**
	 * Plotta ackumulerade investeringar.
	 */
	protected function plotta_ackumulerad_investering(): void {
		imageantialias($this->ackumulerad_graf->graf, true);
		$acknetto = abs($this->ackmax - $this->ackmin);
		$delta = intval(abs($this->ackmin / $acknetto) * 680);

		/**
		 * Rita axlar i svag gul nyans.
		 */
		$this->ackumulerad_graf->sätt_linje(100, 20, 100, 720, $this->graf->gul_v[1]);
		$this->ackumulerad_graf->sätt_linje(80, 700 - $delta, 2120, 700 - $delta, $this->graf->gul_v[1]);

		/**
		 * Indela axeln i segment.
		 */
		for ($index = 0; $index <= 4; $index++) {
			$y = intval($index * (680 - $delta) / 4 + 20);
			$text = strval(intval($this->ackmax - $index * $this->ackmax / 4));
			$this->ackumulerad_graf->sätt_text(15, $y, $text, $this->graf->blå);
		}

		/**
		 * Tabellrubrik i gult, axelvärden i blått.
		 */
		$this->ackumulerad_graf->sätt_text(15, 690, "{$this->ackmin}", $this->graf->blå);
		$this->ackumulerad_graf->sätt_text(120, 20, "Ackumulerat netto", $this->graf->gul);

		$xk1 = -1;
		$yk1 = -1;
		$ack = 0;
		/**
		 * Plotta netton över tid.
		 */
		foreach ($this->investeringar as $index => $inv) {
			$ack += $inv[1];
			$xk2 = 100 + intval(2000 * $index / max(1, ($this->antal_investeringar - 1)));
			$yk2 = 700 - $delta - intval(680 * $ack / $acknetto);
			$färg = ($ack > 0) ? $this->graf->gul : $this->graf->röd;

			if ($xk1 > 0) {
				$this->ackumulerad_graf->sätt_linje($xk1, $yk1, $xk2, $yk2, $färg);
			}

			[$xk1, $yk1] = [$xk2, $yk2];
		}

		$this->ackumulerad_graf->spara_tipsgraf($this->fil_ack);
	}
}
