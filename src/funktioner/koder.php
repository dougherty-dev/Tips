<?php

declare(strict_types=1);

/**
 * Beräkna index för kodbeteckning.
 */
function n_index(int $n): string {
	$retur = '';
	foreach (str_split("$n") as $i) {
		$retur .= INDEX[$i];
	}

	return $retur;
}

/**
 * Beräkna exponent för kodbeteckning.
 */
function n_exponent(int $n): string {
	$retur = '';
	foreach (str_split("$n") as $i) {
		$retur .= EXPONENT[$i];
	}

	return $retur;
}
