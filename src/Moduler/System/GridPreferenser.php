<?php

/**
 * Klass GridPreferenser.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\System;

/**
 * Klass GridPreferenser.
 */
class GridPreferenser extends GridGarderingar {
	/**
	 * Grid för preferenser.
	 */
	public function grid_preferenser(): string {
		/**
		 * Bygg rullgardinsmeny.
		 */
		$generatorsträng = '';
		foreach (RKod::cases() as $kod) {
			$vald = $this->kod->value === $kod->value ? ' selected="selected"' : '';
			$generatorsträng .= t(7, "<option value=\"{$kod->value}\"$vald>System: {$kod->value} ({$kod->garanti()})</option>");
		}

		$pröva_garderingar = $this->pröva_garderingar ? ' checked="checked" ' : '';
		$pröva_reduktion = $this->pröva_reduktion ? ' checked="checked"' : '';
		$kombo = (new Kombo());

		/**
		 * Returnera HTML.
		 */
		return <<< EOT
						<p><label>Garderingar <input type="checkbox" id="pröva_garderingar"$pröva_garderingar></label>
							<label>Reduktion <input type="checkbox" id="pröva_reduktion"$pröva_reduktion></label>
							<select id="system_kod" name="system_kod">
$generatorsträng						</select></p>
{$kombo->kombo()}
						<p><button type="button" id="system_r_schema" value="[{$this->kod->helgarderingar()}, {$this->kod->halvgarderingar()}]">{$this->kod->value}</button></p>
EOT;
	}

	/**
	 * Grid för koddata.
	 */
	public function grid_koddata(): string {
		return <<< EOT
						<h1>Kodanalys</h1>
						<div id="system_kodanalys">
{$this->kod->koddata()}
						</div> <!-- system_kodanalys -->
EOT;
	}
}
