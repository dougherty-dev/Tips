<?php

/**
 * Klass Matchtabeller.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

use Tips\Moduler\TT;

/**
 * Klass Matchtabeller.
 * Rendera tabeller för garderingar/system samt matcher med odds och historik.
 */
final class Matchtabeller extends Garderingstabellrad {
	/**
	 * Grid för TT-matcher.
	 * Tabeller för garderingar/system respektive matchdata.
	 * @return string[]
	 */
	public function matchtabeller(): array {
		$vinstmarkering = new Vinstmarkering();

		/**
		 * Tabeller för odds, streck, sannolikheter, historik och tabindex.
		 */
		$garderingstabell = '';
		$oddstabell = '';
		$tix = range(1000, 999 + 3 * $this->tt::TT_MATCHANTAL);

		/**
		 * Sätt stil på sannolikheter.
		 */
		$oddsstil = stila_tabell($this->tt->tt_o_sannolikheter);
		$streckstil = stila_tabell($this->tt->tt_s_sannolikheter);

		$fördelning = $this->generera_fördelning();

		$teckenfördelning = array_map(fn (array $odds): array =>
			array_map(fn (float $värde): string => stil($värde), $odds), $fördelning);

		$formaterad_dist = formatera_sannolikheter($fördelning);
		$odds = formatera_sannolikheter($this->tt->tt_odds);

		$this->tt->historik->hämta_historik();
		$this->tt->tt_odds_ordnade = ordna_sannolikheter($this->tt->tt_odds);

		/**
		 * Iterera över sannolikheter.
		 */
		foreach (array_keys($this->tt->tt_o_sannolikheter) as $i) {
			$odds_finns = $this->tt->tt_odds[$i][0] == 0 ? ' förlust' : '';
			$sortering = $this->tt->tt_odds_ordnade[$i] + 1;
			$sorteringsstil = stil(1 - $sortering / $this->tt::TT_MATCHANTAL); // gråskala mellan 0 och 1

			$td_rad = $this->td_rad($i, $sortering, $vinstmarkering);

			/**
			 * Grön, gul eller röd bakgrund för vinst, oavgjort, förlust
			 */
			$k = TOM_STRÄNGVEKTOR;
			$vinstmarkering->vinstfärg($k, $this->tt->reduktion[$i], $this->tt->enkelrad_1X2[$i]); // sätt färg

			$garderingstabell .= $this->garderingstabellrad($i, $sortering, $sorteringsstil, $k, $td_rad);

			/**
			 * Definiera tabellrad.
			 */
			$oddstabell .= (new Oddstabellrad($this->tt))->oddstabellrad(
				$i,
				$sorteringsstil,
				$teckenfördelning[$i],
				$formaterad_dist[$i],
				$odds_finns,
				$tix,
				$odds[$i],
				$oddsstil[$i],
				$streckstil[$i]
			);
		}

		return [$garderingstabell, $oddstabell]; // returnera till TTGridMatcher
	}

	/**
	 * Generera TD-rad.
	 */
	private function td_rad(int $i, int $sortering, Vinstmarkering $vinstmarkering): string {
		/**
		 * Iterera över kolumner med garderingsfält.
		 */
		$td_rad = '';
		for ($j = 0; $j < $this->tt::TT_MAX_SPIKFÄLT; $j++) {
			$grå = $j % 2 === 0 ? ' grå' : ''; // omväxlande grå/svart nyans

			/**
			 * Grön, gul eller röd bakgrund för vinst, oavgjort, förlust
			 */
			$k = TOM_STRÄNGVEKTOR;
			$vinstmarkering->vinstfärg($k, $this->tt->spikar[$j][$i], $this->tt->enkelrad_1X2[$i]); // sätt färg

			/**
			 * Definiera tabellkolumn.
			 */
			$td_rad .= <<< EOT
								<td><input tabindex="-1" data-sortering="$sortering" class="tipsruta tt_spik1$grå{$k[0]}" type="text" size="1" name="tt_spikar[$j][$i][0]" value="{$this->tt->spikar[$j][$i][0]}" maxlength="1"></td>
								<td><input tabindex="-1" data-sortering="$sortering" class="tipsruta tt_spik2$grå{$k[1]}" type="text" size="1" name="tt_spikar[$j][$i][1]" value="{$this->tt->spikar[$j][$i][1]}" maxlength="1"></td>
								<td><input tabindex="-1" data-sortering="$sortering" class="tipsruta tt_spik3$grå{$k[2]}" type="text" size="1" name="tt_spikar[$j][$i][2]" value="{$this->tt->spikar[$j][$i][2]}" maxlength="1"></td>

EOT;
		}

		return $td_rad;
	}
}
