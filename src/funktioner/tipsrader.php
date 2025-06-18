<?php

declare(strict_types=1);

/**
 * Beräkna antal rätt för en given rad.
 * @param string|string[] $rad
 */
function antal_rätt(string|array $rad, string $korrekt_rad): int {
	$antal_rätt = 0;
	$radvektor = is_array($rad) ? $rad : str_split($rad);
	foreach ($radvektor as $k => $vektor) {
		if (str_contains($vektor, $korrekt_rad[$k])) {
			$antal_rätt++;
		}
	}
	return $antal_rätt;
}

/**
 * Tipsrad 1X2 till representation på format 012.
 */
function symboler_till_siffror(string $tipsrad): string {
	return str_replace('X', '1', str_replace('1', '0', $tipsrad));
}

/**
 * Tipsrad 012 till representation på format 1X2.
 */
function siffror_till_symboler(string $tipsrad_012): string {
	return str_replace('0', '1', str_replace('1', 'X', $tipsrad_012));
}

/**
 * 1X11… => 1,X,1,1…
 */
function kommatisera(string $tipsrad_012): string {
	return implode(',', str_split(siffror_till_symboler($tipsrad_012)));
}

/**
 * Packa upp tipsrader.
 * @param string[] $tipsrader
 * @return string[]
 */
function bas36till3(array $tipsrader): array {
	return array_map(
		fn (string $tipsrad): string =>
		sprintf('%0' . MATCHANTAL . 'd', base_convert($tipsrad, 36, 3)),
		$tipsrader
	);
}

/**
 * Packa tipsrader.
 * @param string[] $tipsrader
 * @return string[]
 */
function bas3till36(array $tipsrader): array {
	return array_map(
		fn (string $tipsrad): string =>
		base_convert($tipsrad, 3, 36),
		$tipsrader
	);
}
