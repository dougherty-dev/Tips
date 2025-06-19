<?php

/**
 * Klass Parallellisering.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

use Tips\Klasser\Parallellisering\Behandla;

/**
 * Klass Parallellisering.
 */
final class Parallellisering extends Behandla {
	/**
	 * Parallellisera ett skript.
	 */
	public function parallellisera(string $skript = PARALLELLISERING . '/PGenerera.php'): void {
		$this->spel->db->temp->exec("DELETE FROM `parallellisering`");

		for ($i = 1, $delrymd = []; $i <= 4; $i++) {
			$delrymd[$i] = ($this->trådar >= 3 ** $i) ? 2 : 0;
		}

		$rymd = [range(0, $delrymd[1]), range(0, $delrymd[2]), range(0, $delrymd[3]), range(0, $delrymd[4])];

		foreach (generera($rymd, 4) as $räckvidd) {
			[$i, $j, $k, $l] = str_split($räckvidd);
			file_put_contents("a.txt", "i=$i\&j=$j\&k=$k\&l=$l", FILE_APPEND);
			match (PHP_SAPI) {
				'fpm-fcgi' => exec("SCRIPT_FILENAME=$skript \
					REQUEST_METHOD=GET QUERY_STRING=i=$i\&j=$j\&k=$k\&l=$l {$this->fcgi} > /dev/null &"),
				default => exec("{$this->php} $skript -i$i -j$j -k$k -l$l > /dev/null &")
			};
		}
	}
}
