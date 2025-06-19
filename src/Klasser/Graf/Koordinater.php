<?php

/**
 * Klass Koordinater.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Graf;

use Tips\Egenskaper\Eka;

/**
 * Klass Koordinater.
 */
class Koordinater extends Element {
	use Eka;

	/**
	 * Rendera graf för tipsrader.
	 */
	public function rendera_tipsgraf(string $fil): string {
		$tid = time();
		return "<img class=\"tipsgraf\" src=\"{$this->eka(HTML_GRAF)}$fil?$tid\" width=\"{$this->bredd}\" height=\"{$this->höjd}\" alt=\"$fil\">";
	}

	/**
	 * Spara tipsgraf.
	 */
	public function spara_tipsgraf(string $fil): void {
		ob_start();
		imagepng($this->graf);
		file_put_contents(GRAF . $fil, ob_get_contents());
		ob_end_clean();
	}

	/**
	 * Beräkna serienummer för tipsrad.
	 * @param int[] $kuber
	 */
	public function serienummer(string $tipsrad_012, array $kuber = KUBER): int {
		return (int) vektorprodukt($kuber, array_map('intval', str_split($tipsrad_012)));
	}

	/**
	 * Beräkna koordinater för tipsrad.
	 * @param int[] $kuber
	 * @return array<int, int>
	 */
	public function tipsgrafskoordinater(string $tipsrad_012, array $kuber = KUBER): array {
		$serienummer = $this->serienummer($tipsrad_012, $kuber);
		return [$serienummer % $this->bredd, intval(floor($serienummer / $this->bredd))];
	}

	/**
	 * Rendera kors genom koordinat för rätt rad.
	 */
	public function vinstkors(string $tipsrad_012): void {
		if ($tipsrad_012 === '') {
			return;
		}

		[$x, $y] = $this->tipsgrafskoordinater($tipsrad_012);
		$this->sätt_rektangel($x - 5, $y - 5, $x + 5, $y + 5, $this->röd);
		$this->sätt_linje(0, $y, $this->bredd - 1, $y, $this->röd);
		$this->sätt_linje($x, 0, $x, $this->höjd - 1, $this->röd);
	}
}
