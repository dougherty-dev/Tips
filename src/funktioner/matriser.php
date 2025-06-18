<?php

declare(strict_types=1);

/**
 * Odds till decimalodds.
 * @param array<int, float[]> $odds
 * @return array<int, float[]>
 */
function odds_till_sannolikheter(array $odds): array {
	$sannolikheter = [];
	foreach ($odds as $enskilt_odds) {
		$pred = array_map(
			fn (float $värde): float =>
			$värde > 0 ? 1.0 / $värde : 0.0,
			$enskilt_odds
		);

		$sum_p = array_sum($pred);

		$sannolikheter[] = array_map(
			fn (float $värde): float =>
			($sum_p > 0) ? $värde / $sum_p : 0,
			$pred
		);
	}
	return $sannolikheter;
}

/**
 * Decimalodds till odds.
 * @param array<int, float[]> $sannolikheter
 * @return array<int, float[]>
 */
function sannolikheter_till_odds(array $sannolikheter): array {
	return array_map(
		fn (array $odds): array =>
		array_map(
			fn (float $värde): float =>
			($värde > 0) ? 1.0 / $värde : 0.0,
			$odds
		),
		$sannolikheter
	);
}


/**
 * Ta fram sannolikaste raden.
 * @param array<int, float[]> $sannolikheter
 */
function sannolikheter_till_enkelrad_012(array $sannolikheter): string {
	return implode(
		'',
		array_map(
			fn (array $enskild_sannolikhet): string =>
			(string) array_search(ne_max($enskild_sannolikhet), $enskild_sannolikhet, true),
			$sannolikheter
		)
	);
}

/**
 * Formatera sannolikheter med 2 decimaler.
 * @param array<int, float[]> $sannolikheter
 * @return array<int, string[]>
 */
function formatera_sannolikheter(array $sannolikheter, int $decimaler = 2): array {
	return array_map(
		fn (array $enskild_sannolikhet): array =>
		array_map(
			fn (float $värde): string =>
			number_format($värde, $decimaler),
			$enskild_sannolikhet
		),
		$sannolikheter
	);
}

/**
 * Matris till endimensionell vektor.
 * @param array<int, array<int, int[]|float[]>> $matris
 * @return float[]|int[]
 */
function platta_matris(array $matris) {
	$utdata = [];
	foreach ($matris as $vektor) {
		$utdata[] = array_merge(...$vektor);
	}

	return array_merge(...$utdata);
}

/**
 * Återbygg matris från endimensionell vektor.
 * @param string $platt_matris
 * @return array<int, array<int, int[]|float[]>>
 */
function återbygg_matris(string $platt_matris, int $n = 3, int $matchantal = MATCHANTAL) {
	return array_chunk(
		array_chunk(
			array_map(
				'floatval',
				explode(',', $platt_matris)
			),
			max(1, $n)
		),
		max(1, $matchantal)
	);
}

/**
 * Sortera efter högst enskild sannolikhet.
 * @param array<int, float[]> $sannolikheter
 * @return int[]
 */
function ordna_sannolikheter(array $sannolikheter): array {
	$sortering = array_map(
		fn (array $enskild_sannolikhet): float =>
		(float) ne_min($enskild_sannolikhet),
		$sannolikheter
	);

	asort($sortering);
	return array_flip(array_keys($sortering));
}

/**
 * Matris med heltal till matris med flyttal med två decimaler.
 * @param array<int, float[]> $streck
 * @return array<int, float[]>
 */
function streck_till_sannolikheter(array $streck): array {
	return array_map(
		fn (array $match): array =>
		array_map(
			fn (float $enskild_sannolikhet): float =>
			$enskild_sannolikhet / 100,
			$match
		),
		$streck
	);
}

/**
 * Kolla om matris är komplett.
 * @param array<int, float[]> $matris
 */
function matris_saknar_nollelement(array $matris): bool {
	return !in_array(0.0, array_merge(...$matris), true);
}

/**
 * Extrahera klusterkoordinater. (Lägg i Kluster)
 * @return array<int, int[]>
 */
function explodera_koordinater(string $sträng, int $partition, callable $anrop): array {
	$vektor = explode(',', $sträng);
	return array_chunk(array_map($anrop, $vektor), max(1, $partition));
}
