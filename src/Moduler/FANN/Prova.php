<?php

/**
 * Klass Prova.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\FANN;

use Tips\Egenskaper\Tick;

/**
 * Klass Prova.
 */
class Prova extends Prediktera {
	use Tick;

	/**
	 * Pröva tipsrad enligt kriterier.
	 * Släpp igenom rader utanför kriterier enligt filter.
	 */
	public function pröva_tipsrad(string $tipsrad_012): bool {
		return antal_rätt($this->utdata, $tipsrad_012) >= $this->fann_min || $this->tick();
	}

	/**
	 * Annonsera modul.
	 */
	public function annonsera(): string {
		$generator = <<< EOT

							<select id="fann_min_ext">
{$this->generatorsträng(8)}							</select>
EOT;

		return "{$this->fann_rätt} [{$this->fann_min}–" . MATCHANTAL . "] " .
			$this->attraktionsfaktor($this->attraktionsfaktor, 'fann_attraktionsfaktor') . $generator;
	}

	/**
	 * Visa kommentar.
	 */
	public function kommentar(): string {
		return self::class . " {$this->fann_min}–" . MATCHANTAL . " | a={$this->attraktionsfaktor}";
	}

	/**
	 * Beräkna antal rader som omfattas.
	 * Bestäms av halv- och helgarderingar enligt matrisvärden.
	 */
	protected function beräkna_rader(int $fann_min): int {
		$antal_rader = 0;
		for ($index = $fann_min; $index <= MATCHANTAL; $index++) {
			$antal_rader += UTFALL_PER_HALVGARDERINGAR[$this->halvgarderingar][$index];
		}
		return $antal_rader;
	}

	/**
	 * Gemensam sträng för rullgardinsmeny.
	 * Används på modulsida samt vid modulannonser på huvudsida.
	 */
	protected function generatorsträng(int $indrag): string {
		$generatorsträng = '';

		for ($index = MATCHANTAL; $index >= self::FANN_MIN; $index--) {
			$antal_rader = $this->beräkna_rader($index);
			$fann_selected_min = ($this->fann_min === $index) ? ' selected="selected"' : '';
			$generatorsträng .= t($indrag, "<option value=\"$index\"$fann_selected_min>$index ($antal_rader)</option>");
		}

		return $generatorsträng;
	}
}
