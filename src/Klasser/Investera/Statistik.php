<?php

/**
 * Klass Statistik.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Investera;

/**
 * Klass Statistik.
 * Ackumulerad statistik över antal rätt i alla vinstkategorier.
 */
class Statistik extends Hamta {
	protected string $tabell = '';

	/**
	 * Visa statistik i tabell.
	 * @param int[] $vinstmatris
	 */
	protected function statistik(array $vinstmatris): void {
		/**
		 * Bygg andelsmatris.
		 */
		$andelsmatris = TOM_VINSTMATRIS;
		if ($this->antal_investeringar > 0) {
			$andelsmatris = array_map(fn (float $n): string =>
				number_format(100 * $n / $this->antal_investeringar, 2), $vinstmatris);
		}

		$this->tabell .= <<< EOT
						</table>
						<br>
						<table>
							<tr class="match">
								<th>📈</th>
								<th>13 r</th><th>12 r</th><th>11 r</th><th>10 r</th>
							</tr>
							<tr class="höger">
								<th class="match center">Antal</th>
								<td>{$vinstmatris[13]}</td><td>{$vinstmatris[12]}</td>
								<td>{$vinstmatris[11]}</td><td>{$vinstmatris[10]}</td>
							</tr>
							<tr class="höger">
								<th class="match center">Andel %</th>
								<td>{$andelsmatris[13]}</td><td>{$andelsmatris[12]}</td>
								<td>{$andelsmatris[11]}</td><td>{$andelsmatris[10]}</td>
							</tr>
						</table>
EOT;
	}

	/**
	 * Ge stil åt vinstrader.
	 * @param int[] $vinstdata
	 * @return string[]
	 */
	protected function vinststil(array $vinstdata): array {
		$vinstmatris = array_map(fn (int $index): string =>
			$vinstdata[$index] > 0 ? " class=\"vinst$index\"" : '', array_keys($vinstdata));

		return array_reverse($vinstmatris);
	}
}
