<?php

/**
 * Egenskap KodMatris.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Egenskaper;

/**
 * Egenskap KodMatris.
 * Hantera matrisdata.
 * För närvarande bara för enstaka täckningskoder.
 */
trait KodMatris {
	/**
	 * Rendera matrisdata för kod i MathML.
	 * Generatormatriser, paritetsmatriser med flera.
	 * @param array<int, int[]> $matris
	 */
	private function matris_till_mathml(array $matris, string $typ, int $tab = 0): string {
		$mathml = <<< EOT
<math>
	<mi>$typ</mi>
	<mo>=</mo>
	<mrow>
		<mo>(</mo>
		<mtable>

EOT;

		/**
		 * Matrisrad
		 */
		foreach ($matris as $rader) {
			$mathml .= <<< EOT
			<mtr>

EOT;

			/**
			 * Matriskolumn
			 */
			foreach ($rader as $kolumn) {
				$mathml .= <<< EOT
				<mtd><mn>$kolumn</mn></mtd>

EOT;
			}

			$mathml .= <<< EOT
			</mtr>

EOT;
		}

		$mathml .= <<< EOT
		</mtable>
		<mo>)</mo>
	</mrow>
</math>
EOT;

		/**
		 * Indrag
		 */
		$text = '';
		foreach (explode("\n", $mathml) as $rad) {
			$text .= t($tab, $rad);
		}

		return rtrim($text);
	}
}
