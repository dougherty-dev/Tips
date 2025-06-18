<?php

declare(strict_types=1);

/**
 * Finns ett värde inom ett intervall inklusivt?
 */
function in(int|float $värde, int|float $min, int|float $max): bool {
	return $värde >= $min && $värde <= $max;
}

/**
 * Max med kontroll för tom vektor.
 * @param int[]|float[] $vektor
 */
function ne_max(array $vektor): int|float {
	return $vektor === [] ? 0 : max($vektor);
}

/**
 * Min med kontroll för tom vektor.
 * @param int[]|float[] $vektor
 */
function ne_min(array $vektor): int|float {
	return $vektor === [] ? 0 : min($vektor);
}

/**
 * Plocka ut och kontrollera variabler från requeststräng.
 * @return array<int, int>
 */
function extrahera(): array {
	$ret = [];
	$opts = isset($_REQUEST['i']) ? $_REQUEST : (array) getopt("i:j:k:l:");

	foreach ($opts as $opt) {
		$ret[] = is_string($opt) ? intval($opt) : 0;
	}
	return $ret;
}

/**
 * a1·b1 + a2·b2 + …
 * @param array<int|float> $vektor1
 * @param array<int|float> $vektor2
 */
function vektorprodukt(array $vektor1, array $vektor2): int|float {
	return array_sum(
		array_map(
			fn (int|float $tal1, int|float $tal2): int|float =>
			$tal1 * $tal2,
			$vektor1,
			$vektor2
		)
	);
}

/**
 * Sträng med «svenska» decimaler till korrekt flyttalsform.
 */
function flyttal(string $str): float {
	return floatval(str_replace(',', '.', $str));
}

/**
 * Rendera tal som procentvärde.
 */
function procenttal(float $värde): int {
	return intval(100 * round($värde, 2));
}

/**
 * Tabulera indrag i HTML.
 */
function t(int $n, string $text): string {
	return str_repeat("\t", $n) . $text . PHP_EOL;
}

/**
 * Extrahera spelstopp från JSON-data.
 * @return string[]
 */
function spelstopp(string $utc): array {
	$datum = '';
	$spelstopp = '';
	$dag = '';

	if ($utc != null) {
		$stopp = explode('T', $utc);
		$datum = $stopp[0];
		$format = new IntlDateFormatter(pattern:'EEEE', locale:'sv_SE', dateType:IntlDateFormatter::FULL, timeType:IntlDateFormatter::NONE);
		$dag = ucfirst((string) $format->format((int) strtotime($datum)));
		$spelstopp = explode('+', $stopp[1])[0];
	}
	return [$datum, $spelstopp, $dag];
}

/**
 * Hämta JSON-objekt från en server.
 */
function hämta_objekt(string $url): ?object {
	// return json_decode((string) file_get_contents(JSONFIL));
	$json = null;
	$handle = curl_init($url);
	if ($handle !== false && $url !== '') {
		curl_setopt($handle, CURLOPT_HEADER, false);
		curl_setopt($handle, CURLOPT_USERAGENT, USERAGENT);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($handle, CURLOPT_URL, $url);
		$json = curl_exec($handle);
		unset($handle);
	}

	if ($json) {
		file_put_contents(JSONFIL, $json);
	}

	$retur = is_string($json) ? json_decode($json) : null;
	return (!is_object($retur)) ? null : $retur;
}
