<?php

declare(strict_types=1);

/**
 * Ge stil åt tipsrutor i nyansskala.
 */
function stil(float $fraktion): string {
	$färg = ($fraktion < 0.5) ? 'black' : 'white';
	$bfärg = sprintf('%02x', 255 * (1 - $fraktion));
	return ' style="background-color: #' . "$bfärg$bfärg$bfärg" .
		'; text-align: right; padding-left: 0.3em; padding-right: 0.3em; color: ' . $färg . ';"';
}

/**
 * Lägg stil på element i tabell.
 * @param array<int, float[]> $sannolikheter
 * @return array<int, string[]>
 */
function stila_tabell(array $sannolikheter, int $decimaler = 2): array {
	$td_rad = array_fill(0, count($sannolikheter), TOM_STRÄNGVEKTOR);
	foreach ($sannolikheter as $i => $sannolikhet) {
		foreach ($sannolikhet as $j => $enskild_sannolikhet) {
			$stil = stil($enskild_sannolikhet);
			$sann_ut = number_format($enskild_sannolikhet, $decimaler);
			$td_rad[$i][$j] = "<td$stil>$sann_ut</td>";
		}
	}

	return $td_rad;
}

/**
 * Rstil.
 */
function rstil(float $sannolikhet, float $smax, float $smin, string $klass = ''): string {
	return match ($sannolikhet) {
		$smax => " class=\"{$klass}vinst\"",
		$smin => " class=\"{$klass}storförlust\"",
		default => " class=\"{$klass}förlust\""
	};
}
