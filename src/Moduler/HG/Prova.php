<?php

/**
 * Klass Prova.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\HG;

use Tips\Egenskaper\Tick;
use Tips\Moduler\HG\Konstanter;

/**
 * Klass Prova.
 */
class Prova extends Fordelning {
	use Tick;
	use Konstanter;

	public int $hg_rätt = 0;

	/**
	 * Pröva tipsrad.
	 */
	public function pröva_tipsrad(string $tipsrad_012): bool {
		return ($this->hg_rätt = antal_rätt($this->utdata, $tipsrad_012)) >= $this->hg_min || $this->tick();
	}

	/**
	 * Annonsera modul.
	 */
	public function annonsera(): string {
			$generator = <<< EOT

							<select id="hg_min_ext">
{$this->generatorsträng(8)}							</select>
EOT;
		return "{$this->hg_rätt} [{$this->hg_min}–" . MATCHANTAL . "] " .
			$this->attraktionsfaktor($this->attraktionsfaktor, 'hg_attraktionsfaktor') . $generator;
	}

	/**
	 * Visa kommentar.
	 */
	public function kommentar(): string {
		return self::class . " {$this->hg_min}–" . MATCHANTAL . " | a={$this->attraktionsfaktor}";
	}

	/**
	 * Gemensam sträng för rullgardiner.
	 */
	protected function generatorsträng(int $indrag): string {
		for ($i = MATCHANTAL, $generatorsträng = ''; $i >= self::HG_MIN; $i--) {
			$hg_element = self::HG_MATRIS[$i];
			$hg_selected = ($this->hg_min === $i) ? ' selected="selected"' : '';
			$generatorsträng .= t($indrag, "<option value=\"$i\"$hg_selected>$i ({$hg_element[0]})</option>");
		}
		return $generatorsträng;
	}
}
