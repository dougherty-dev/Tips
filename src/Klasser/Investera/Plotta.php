<?php

/**
 * Klass Plotta.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Investera;

use Tips\Klasser\Graf;

/**
 * Klass Plotta.
 */
class Plotta extends HamtaAlla {
	protected string $fil = '/investeringsgraf.png';
	protected Graf $graf;

	/**
	 * Plotta data för lagda spel; utgifter och vinster.
	 */
	protected function plotta_investeringar(): void {
		/**
		 * Skiss:
		 *	0, 0							2186, 0
		 *		100, 20					2100, 20
		 *					680 x 2000
		 *		100, 700				2100, 700
		 *	728, 0							2186, 0

		 * Axlar i gult.
		 */
		$this->graf->sätt_linje(100, 20, 100, 720, $this->graf->gul_v[1]);
		$this->graf->sätt_linje(80, 700, 2120, 700, $this->graf->gul_v[1]);

		/**
		 * Axeletiketter i blått.
		 */
		for ($index = 0; $index <= 4; $index++) {
			$ykoord = intval($index * 670 / 4 + 20);
			$text = strval(intval($this->maxvinst - $index * $this->maxvinst / 4));
			$this->graf->sätt_text(15, $ykoord, $text, $this->graf->blå);
		}
		$this->graf->sätt_text(120, 20, "Vinster", $this->graf->gul);

		/**
		 * Ringar i olika storlek och färg bereonde på vinstnivå.
		 */
		foreach ($this->investeringar as $index => $inv) {
			[$diameter, $färg] = match (true) {
				$inv[0] > 5000000 => [40, $this->graf->vit],
				$inv[0] > 1000000 => [35, $this->graf->blå],
				$inv[0] > 500000 => [28, $this->graf->lila],
				$inv[0] > 100000 => [22, $this->graf->röd],
				$inv[0] > 50000 => [18, $this->graf->grön],
				$inv[0] > 10000 => [14, $this->graf->gul],
				$inv[0] > 5000 => [10, $this->graf->gul_v[5]],
				$inv[0] > 1000 => [8, $this->graf->gul_v[4]],
				$inv[0] > 500 => [5, $this->graf->gul_v[3]],
				$inv[0] > 1 => [3, $this->graf->gul_v[2]],
				default => [2, $this->graf->gul_v[1]]
			};
			$xk1 = 100 + intval(2000 * $index / max(1, ($this->antal_investeringar - 1)));
			$yk1 = 700 - intval(680 * $inv[0] / $this->maxvinst);

			$this->graf->sätt_cirkel($xk1, $yk1, $diameter, $färg);
		}

		$this->graf->spara_tipsgraf($this->fil);
	}
}
