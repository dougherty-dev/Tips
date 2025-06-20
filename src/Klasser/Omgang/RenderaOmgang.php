<?php

/**
 * Klass RenderaOmgang.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Omgang;

use Tips\Klasser\Tips;
use Tips\Egenskaper\Eka;

/**
 * Klass RenderaOmgang.
 */
final class RenderaOmgang {
	use Eka;

	/**
	 * Init.
	 */
	public function __construct(private Tips $tips) {
	}

	/**
	 * Eka ut resultatet i HTML.
	 */
	public function rendera_html(
		string $grid_omgång,
		string $föregående,
		string $nästa,
		int $antal_kompletta,
		int $antal_omgångar
	): string {
		/**
		 * Eka ut resultatet i HTML.
		 */
		return <<< EOT
						<a href="/"><div class="logotyp {$this->tips->spel->speltyp->produktnamn()}">
							<img src="/img/ss.svg" height="30" class="ss-logo" alt="Svenska spel">
							<img src="/img/{$this->tips->spel->speltyp->produktnamn()}.svg" height="45" alt="{$this->tips->spel->speltyp->produktnamn()}">
						</div></a>
						<form id="manuell">
							<span style="font-size: 2em; vertical-align: middle;">{$this->eka($this->tips->spel->komplett ? '✅' : '❌')}</span>
							<select id="genererad_omgång">
$grid_omgång							</select>
							<input type="text" autocomplete="off" id="manuell_omgång" size="6" value="">
							<input type="submit" value="Manuell"><br>
						</form>
						<button{$this->eka($föregående ? '' : ' disabled="disabled"')} id="föregående" value="$föregående">⇦</button>
						<button{$this->eka($nästa ? '' : ' disabled="disabled"')} id="nästa" value="$nästa">⇨</button>
						($antal_kompletta / $antal_omgångar)
EOT;
	}
}
