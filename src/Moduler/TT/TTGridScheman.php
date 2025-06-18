<?php

/**
 * Klass TTGridSchema.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

/**
 * Klass TTGridSchema.
 */
final class TTGridScheman {
	/**
	 * Grid för TT-scheman.
	 */
	public function tt_grid_scheman(): string {
		return <<< EOT
						<p><button id="tt_rensa_spikar">Rensa</button> |
							<button type="button" class="tt_kombo" value="[1, [2, 2], [1], [0], [3, 4, 48, 1]]">R_3_4_48_1 + F 1/1</button>
							<button type="button" class="tt_kombo" value="[1, [3, 4], [2], [0], [6, 0, 73, 1]]">R_6_0_73_1 + F 2/2</button>
							<button type="button" class="tt_kombo" value="[0, [0, 0], [0], [0], [8, 0, 486, 1]]">R_8_0_486_1</button></p>
						<p><button type="button" class="tt_schema" value="[1, [1, 8], [8], [1]]">FX 8/8</button>
							<button type="button" class="tt_schema" value="[1, [1, 8], [8], [0]]">F 8/8</button>
							<button type="button" class="tt_schema" value="[1, [1, 3], [2], [0]]">F 2/3</button>
							<button type="button" class="tt_schema" value="[2, [1, 4, 1, 4], [4, 3], [1, 0]]">FX 4/4 + F 3/4</button>
							<button type="button" class="tt_schema" value="[3, [1, 3, 1, 3, 4, 6], [2, -1, 2], [1, 1, 1]]">FX 2/3 + SX 1/3 + FX 2/3</button></p>
EOT;
	}
}
