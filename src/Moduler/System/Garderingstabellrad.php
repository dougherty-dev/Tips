<?php

/**
 * Klass Garderingstabellrad.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\System;

use Tips\Klasser\Utdelning;
use Tips\Klasser\Prediktioner;
use Tips\Klasser\Matcher;

/**
 * Klass Garderingstabellrad.
 */
final class Garderingstabellrad {
	/**
	 * Garderingar för system.
	 */
	public function __construct(
		protected Utdelning $utdelning,
		protected Prediktioner $odds,
		protected Prediktioner $streck,
		protected Matcher $matcher
	) {
	}

	/**
	 * Tabellrad för garderingar.
	 * @param string[] $vinstklass
	 * @param string[] $reduktion
	 * @param string[] $k
	 */
	public function garderingstabellrad(
		int $i,
		array $vinstklass,
		array $reduktion,
		array $k,
		string $td_rad
	): string {
		$matchindex = $i + 1;
		$vinsttecken = $this->utdelning->har_tipsrad ? $this->utdelning->tipsrad[$i] : '';

		return <<< EOT
							<tr class="system-match">
								<td class="match höger ix1" id="system_sortering{$this->odds->sortering[$i]}">$matchindex</td>
								<td{$this->odds->sorteringsstil[$i]} class="vänster">{$this->matcher->match[$i]}</td>
								<td{$this->odds->sorteringsstil[$i]} class="center" id="system_enkelrad{$this->odds->sortering[$i]}">{$this->odds->enkelrad[$i]}</td>
$td_rad								<td{$vinstklass[0]}><input tabindex="-1" data-sortering="{$this->odds->sortering[$i]}"
								data-ruta="1" class="tipsruta system_r grå{$k[0]}" type="text" size="1" name="reduktion[$i][0]" value="{$reduktion[0]}" maxlength="1"></td>
								<td{$vinstklass[1]}><input tabindex="-1" data-sortering="{$this->odds->sortering[$i]}" data-ruta="X" class="tipsruta system_r grå{$k[1]}" type="text" size="1" name="reduktion[$i][1]" value="{$reduktion[1]}" maxlength="1"></td>
								<td{$vinstklass[2]}><input tabindex="-1" data-sortering="{$this->odds->sortering[$i]}" data-ruta="2" class="tipsruta system_r grå{$k[2]}" type="text" size="1" name="reduktion[$i][2]" value="{$reduktion[2]}" maxlength="1"></td>
								<td class="system-helgardering">🡄</td>
								<th class="system-rensa-gardering">🞭</th>
								<td class="vinstrad">$vinsttecken</td>
								<td{$this->odds->sorteringsstil[$i]} class="höger">{$this->odds->sortering[$i]}</td>
							</tr>

EOT;
	}
}
