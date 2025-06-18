<?php

/**
 * Klass GridRader.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Omgang;

use Tips\Klasser\Tips;
use Tips\Egenskaper\Eka;
use Tips\Egenskaper\Varden;
use Tips\Inkludera\Konstanter;

/**
 * Klass GridRader.
 */
final class GridRader {
	use Eka;
	use Varden;
	use Konstanter;

	/**
	 * Hämta värden från egenskap.
	 */
	public function __construct(private Tips $tips) {
		$this->hämta_värden($this->tips->spel->db);
	}

	/**
	 * Visa antal rader samt intervall.
	 */
	public function visa(): string {
		$stryktips = $this->tips->spel->speltyp->produktnamn() === 'stryktipset' ? ' selected="selected"' : '';
		$europatips = $this->tips->spel->speltyp->produktnamn() === 'europatipset' ? ' selected="selected"' : '';
		return <<< EOT
						<p><select id="speltyp">
							<option value="1"$stryktips>Stryktipset</option>
							<option value="2"$europatips>Europatipset</option>
						</select></p>
						<p><input class="nummer" type="number" min="0" max="{$this->eka(strval(MAX_RADER))}" step="100" autocomplete="off" id="max_rader" value="{$this->max_rader}"> rader</p>
						<p><input class="nummer" type="number" min="{$this->eka(strval(self::U13_MIN_MIN))}" max="{$this->eka(strval(self::U13_MIN_MAX))}" step="1000" id="u13_min" value="{$this->u13_min}">–
							<input class="nummer" type="number" min="{$this->eka(strval(self::U13_MAX_MIN))}" max="{$this->eka(strval(self::U13_MAX_MAX))}" step="1000" id="u13_max" value="{$this->u13_max}"></p>
EOT;
	}
}
