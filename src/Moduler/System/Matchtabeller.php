<?php

/**
 * Klass Matchtabeller.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\System;

use Tips\Moduler\System;

/**
 * Klass Matchtabeller.
 * Rendera tabeller för garderingar/system samt matcher med odds och historik.
 */
class Matchtabeller extends Prova {
	/**
	 * Matchtabeller.
	 * Tabeller för garderingar/system respektive matchdata.
	 * @return string[]
	 */
	protected function matchtabeller(): array {
		$vinstmarkering = new Vinstmarkering();

		$garderingstabell = ''; // Tabell för garderingar samt system
		$oddstabell = ''; // Tabell för odds, streck, sannolikheter och historik

		$oddsstil_grund = array_fill(0, 3, ' class="oddskolumn odefinierad"'); // definiera mall för oddsstil
		$streckstil_grund = array_fill(0, 3, ' class="streckkolumn odefinierad"'); // definiera mall för streckstil

		$odds = formatera_sannolikheter($this->odds->prediktioner); // formaterade oddssannolikheter

		/**
		 * Bygg tabell över varje match.
		 */
		foreach (array_keys($this->odds->sannolikheter) as $i) {
			$oddsstil = $oddsstil_grund; // återställ till grundkonfiguration
			$streckstil = $streckstil_grund; // återställ till grundkonfiguration

			/**
			 * Lägg stil för vinst/förlust om omgången är färdigspelad.
			 */
			if ($this->utdelning->har_tipsrad) {
				$tecken = $this->utdelning->tipsrad_012[$i]; // numeriskt tecken 0, 1, 2

				/**
				 * Sätt stil på oddssannolikheter.
				 */
				$oddsstil[$tecken] = rstil(
					$this->odds->sannolikheter[$i][$tecken],
					$this->odds->maxsannolikheter[$i],
					$this->odds->minsannolikheter[$i],
					'oddskolumn '
				);

				/**
				 * Sätt stil på strecksannolikheter.
				 */
				$streckstil[$tecken] = rstil(
					$this->streck->sannolikheter[$i][$tecken],
					$this->streck->maxsannolikheter[$i],
					$this->streck->minsannolikheter[$i],
					'streckkolumn '
				);
			}

			$td_rad = ''; // enskild rad i tabell
			for ($j = 0; $j < self::SYSTEM_MAX_ANTAL_FÄLT; $j++) {
				$grå = $j % 2 === 0 ? ' grå' : ''; // omväxlande grå/svart nyans

				$k = TOM_STRÄNGVEKTOR; // $k definierar grön, gul eller röd bakgrund för vinst, oavgjort, förlust
				$vinstklass = $k; // $vinstklass definierar röd/vit ruta för bommad/infriad gardering
				$vinstmarkering->vinstfärg($k, $this->garderingar[$j][$i], $this->odds->enkelrad[$i]); // sätt färg

				$this->sätt_klass($i, $this->garderingar[$j][$i], $vinstklass); // sätt ruta

				$td_rad .= <<< EOT
								<td{$vinstklass[0]}><input tabindex="-1" data-sortering="{$this->odds->sortering[$i]}" class="tipsruta gardering1$grå{$k[0]}" type="text" size="1" name="garderingar[$j][$i][0]" value="{$this->garderingar[$j][$i][0]}" maxlength="1"></td>
								<td{$vinstklass[1]}><input tabindex="-1" data-sortering="{$this->odds->sortering[$i]}" class="tipsruta gardering2$grå{$k[1]}" type="text" size="1" name="garderingar[$j][$i][1]" value="{$this->garderingar[$j][$i][1]}" maxlength="1"></td>
								<td{$vinstklass[2]}><input tabindex="-1" data-sortering="{$this->odds->sortering[$i]}" class="tipsruta gardering3$grå{$k[2]}" type="text" size="1" name="garderingar[$j][$i][2]" value="{$this->garderingar[$j][$i][2]}" maxlength="1"></td>

EOT;
			}

			$k = TOM_STRÄNGVEKTOR; // $k definierar grön, gul eller röd bakgrund för vinst, oavgjort, förlust
			$vinstklass = $k; // $vinstklass definierar röd/vit ruta för bommad/infriad gardering
			$vinstmarkering->vinstfärg($k, $this->reduktion[$i], $this->odds->enkelrad[$i]); // sätt färg

			$this->sätt_klass($i, $this->reduktion[$i], $vinstklass); // sätt ruta

			$garderingstabell .= (new Garderingstabellrad($this->utdelning, $this->odds, $this->streck, $this->matcher))->garderingstabellrad($i, $vinstklass, $this->reduktion[$i], $k, $td_rad); // fyll garderingstabell

			$oddstabell .= (new Oddstabellrad($this->odds, $this->streck, $this->matcher))->oddstabellrad($i, $oddsstil, $odds, $streckstil); // fyll oddstabell
		}

		return [$garderingstabell, $oddstabell]; // returnera till GridGarderingar
	}

	/**
	 * Gemensam rutin för att bestämma klass.
	 * Sätt röd ruta runt förustmatcher, vit runt vinstmatcher.
	 * @param string[] $reduktion
	 * @param string[] $vinstklass
	 */
	private function sätt_klass(int $i, array $reduktion, array &$vinstklass): void {
		if ($this->utdelning->har_tipsrad && $reduktion !== TOM_STRÄNGVEKTOR) {
			$vinstklass[$this->utdelning->tipsrad_012[$i]] = $reduktion[(int) $this->utdelning->tipsrad_012[$i]] ?
				' class="vinstruta"' : ' class="förlustruta"'; // sätt klass för vinnande teckenposition
		}
	}
}
