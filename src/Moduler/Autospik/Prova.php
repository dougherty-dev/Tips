<?php

/**
 * Klass AutProvaospik.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Autospik;

use Tips\Egenskaper\Tick;

/**
 * Klass Prova.
 */
class Prova extends Spikar {
	use Tick;

	/**
	 * Pröva tipsrad.
	 */
	public function pröva_tipsrad(string $tipsrad_012): bool {
		$this->teckenindex = array_map(fn (int $spik): int => (int) $tipsrad_012[$spik], $this->spikar);
		return $this->teckenindex === $this->oddsindex || $this->tick();
	}

	/**
	 * Annonsera modul.
	 */
	public function annonsera(): string {
		$generator = <<< EOT

							<select id="autospik_ext">
{$this->generatorsträng(8)}							</select>
EOT;
		$spikar = array_map(fn (int $spik): int => $spik + 1, $this->spikar);
		return "{$this->valda_spikar} (" . implode(', ', $spikar) . ') ' .
			$this->attraktionsfaktor($this->attraktionsfaktor, 'autospik_attraktionsfaktor') . $generator;
	}

	/**
	 * Visa kommentar.
	 */
	public function kommentar(): string {
		return self::class . " {$this->valda_spikar} | a={$this->attraktionsfaktor}";
	}
}
