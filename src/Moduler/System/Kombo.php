<?php

/**
 * Klass Kombo.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\System;

/**
 * Klass Kombo.
 */
final class Kombo {
	/**
	 * Förinställda val av garderingar och R-system.
	 */
	public function kombo(): string {
		return <<< EOT
						<br>
						<p><button id="system_rensa_system">Rensa</button> |
							<button type="button" class="system_kombo" value="[1, [1, 4], [2], [0], [4, 5, 238, 1]]">R_4_5_238_1 + F 2/4</button>
							<button type="button" class="system_kombo" value="[2, [1, 5, 1, 5], [5, 3], [1, 0], [8, 0, 486, 1]]">R_8_0_486_1 + FX 5/5 + F 3/5</button></p>
						<p><button type="button" class="system_kombo" value="[2, [1, 6, 1, 6], [6, 4], [1, 0], [7, 0, 186, 1]]">R_7_0_186_1 + FX 6/6 + F 4/6</button>
							<button type="button" class="system_schema" value="[2, [1, 13, 1, 5], [13, 3], [1, 0]]">FX 13/13 + F 3/5</button>
							<button type="button" class="system_schema" value="[3, [6, 13, 1, 5, 1, 5], [8, -1, 3], [0, 1, 0]]">F 8/8 + SX 1/5 + F 3/5</button></p>
						<p><button type="button" class="system_schema" value="[1, [1, 4], [3], [0]]">F 3/4</button>
							<button type="button" class="system_schema" value="[2, [1, 5, 6, 9], [4, 2], [0, 0]]">F 4/5 + F 2/4</button>
							<button type="button" class="system_schema" value="[1, [1, 5], [5], [1]]">FX 5/5</button>
							<button type="button" class="system_schema" value="[1, [1, 3], [-1, 3], [1]]">SX 1/3</button>
							<button type="button" class="system_schema" value="[4, [1, 3, 4, 6, 7, 9, 10, 13], [3, 1, -1, -2], [1, 0, 0, 1]]">FX 3/3 + F 1/3 + S 1/3 + SX 2/4</button></p>
						<hr>
EOT;
	}
}
