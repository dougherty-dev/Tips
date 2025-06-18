<?php

/**
 * Klass PackaData.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Ajax;

/**
 * Klass PackaData.
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
 */
class PackaData {
	/**
	 * Packa data för scheman.
	 * @return array<int, string[]>
	 */
	protected function packa_data(string $request): array {
		if (!is_string($_REQUEST[$request])) {
			return [];
		}

		parse_str($_REQUEST[$request], $sdata);
		$parsedata = (array) filter_var_array((array) $sdata['modul'], FILTER_SANITIZE_SPECIAL_CHARS);
		ksort($parsedata);

		$moduldata = [];
		foreach ($parsedata as $modul => $faktor) {
			$moduldata[] = "$modul:$faktor";
		}

		$data = [];
		foreach ($sdata as $k => $värde) {
			if (is_string($värde)) {
				$data[] = "$k:$värde";
			}
		}

		return [$data, $moduldata];
	}
}
