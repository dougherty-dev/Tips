<?php

/**
 * Klass TTGridPreferenser.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

use Tips\Moduler\TT;

/**
 * Klass TTGridPreferenser.
 * Inställningar för spelet.
 */
final class TTGridPreferenser {
	/**
	 * Initiera.
	 */
	public function __construct(private TT $tt) {
	}

	/**
	 * Grid för TT-preferenser.
	 */
	public function tt_grid_preferenser(): string {
		/**
		 * Rullgardin med särskilda täckningskoder.
		 */
		$tt_kod = '';
		foreach (TTKod::cases() as $kod) {
			$vald = $this->tt->kod->value === $kod->value ? ' selected="selected"' : '';
			$tt_kod .= t(7, "<option value=\"{$kod->value}\"$vald>Kod: {$kod->value} ({$kod->antal_rader()}, r = {$kod->täckning()})</option>");
		}

		/**
		 * Rullgardin med reducerade koder upp till åtta matcher.
		 */
		$tt_rkod = '';
		foreach (TTRKod::cases() as $rkod) {
			$vald = $this->tt->rkod->value === $rkod->value ? ' selected="selected"' : '';
			$tt_rkod .= t(7, "<option value=\"{$rkod->value}\"$vald>System: {$rkod->value} ({$rkod->garanti()})</option>");
		}

		/**
		 * Knappval för vad som ska prövas.
		 */
		$prop = array_fill_keys([
				'tt_pröva_spikar',
				'tt_pröva_täckning',
				'tt_pröva_t_intv',
				'tt_pröva_o_intv',
				'tt_pröva_reduktion'
		], '');

		foreach (array_keys($prop) as $metod) {
			$prop[$metod] = $this->tt->$metod ? ' checked="checked" ' : '';
		}

		/**
		 * Eka ut HTML.
		 */
		return <<< EOT
						<p><label>Spikar <input type="checkbox" id="tt_pröva_spikar"{$prop['tt_pröva_spikar']}></label>
							<label>Teckenintervall <input type="checkbox" id="tt_pröva_t_intv"{$prop['tt_pröva_t_intv']}></label>
							<label>Oddsintervall <input type="checkbox" id="tt_pröva_o_intv"{$prop['tt_pröva_o_intv']}></label>
							<label>Kod <input type="checkbox" id="tt_pröva_täckning"{$prop['tt_pröva_täckning']}></label>
							<label>R-system <input type="checkbox" id="tt_pröva_reduktion"{$prop['tt_pröva_reduktion']}></label></p>
						<p><select id="tt_kod" name="tt_kod">
$tt_kod						</select>
							<select id="tt_rkod" name="tt_rkod">
$tt_rkod						</select></p>
						<form id="generera_topptips" method="post" action="/#modulflikar-TT">
							<p><button class="generera" name="generera_topptips">⚽️ Generera {$this->tt->antal_rader} rader</button>
							<button type="button" id="tt_r_schema" value="[{$this->tt->rkod->helgarderingar()}, {$this->tt->rkod->halvgarderingar()}]">{$this->tt->rkod->value}</button></p>
						</form>
EOT;
	}
}
