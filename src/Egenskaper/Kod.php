<?php

/**
 * Egenskap Kod.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Egenskaper;

/**
 * Egenskap Kod.
 * Information för vissa täckningskoder (BCH-koder för topptipset)
 */
trait Kod {
	use KodMatris;

	/**
	 * Visa generell information om kod.
	 * @param array<int, int[]> $generatormatris
	 * @param array<int, int[]> $kontrollmatris
	 * @param array<int, int[]> $matris
	 * @param int[] $bch
	 * @param string[] $text
	 */
	private function visa_kodinformation(
		array $generatormatris,
		array $kontrollmatris,
		array $matris,
		array $bch, // [n, b, d, F]
		int $tab, // indrag
		array $text,
		string $vektordistribution = ''
	): string {

		/**
		 * Exponent och index.
		 */
		$bch_bi = n_index($bch[3]); // 𝔽₃
		$bch_ke = n_exponent($bch[1]); // 3⁶

		/**
		 * Karakteristik.
		 */
		$redundans = $bch[0] - $bch[1];
		$volym = number_format($volym_s = pow($bch[3], $bch[1]), 0, ',', ' ');
		$rymd = number_format($rum = pow($bch[3], $bch[0]), 0, ',', ' ');
		$reduktion = number_format(fdiv($rum, $volym_s), 0, ',', ' ');

		/**
		 * Sammanställ data.
		 */
		$kodinformation = <<< EOT
<h2>$text[0]:</h2>
<p class="större">$text[2]<br>
	{$text[3]} [{$bch[0]}, {$bch[1]}, {$bch[2]}]$bch_bi{$text[4]} över 𝔽$bch_bi<br>
	M = {$bch[3]}$bch_ke = $volym; <span class="vinst10">R = {$text[1]}</span><br>
	n = {$bch[0]}; k = {$bch[1]}; d = {$bch[2]}; redundans = $redundans<br>
	Rymd: $rymd; reduktion: $reduktion<br>
	GAP/Guava: <code>{$text[5]}</code></p>
EOT;

		/**
		 * Indrag med tab
		 */
		$info = '';
		foreach (explode("\n", $kodinformation) as $t) {
			$info .= t($tab, $t);
		}
		$info = rtrim($info);

		/**
		 * Viktdistribution.
		 */
		$viktdistribution = rtrim(t($tab, "<p>Viktdistribution: $vektordistribution</p>"));

		/**
		 * Leverera text.
		 */
		return <<< EOT
$info
{$this->matris_till_mathml($generatormatris, 'G', $tab)}
{$this->matris_till_mathml($kontrollmatris, 'H', $tab)}
{$this->matris_till_mathml($matris, 'K', $tab)}
$viktdistribution
EOT;
	}
}
