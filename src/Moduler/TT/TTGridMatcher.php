<?php

/**
 * Klass TTGridMatcher.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

use Tips\Moduler\TT;

/**
 * Klass TTGridMatcher.
 */
final class TTGridMatcher extends Matchdataform {
	/**
	 * Initiera.
	 */
	public function __construct(public TT $tt) {
	}

	/**
	 * Grid för TT-matcher.
	 */
	public function tt_grid_matcher(): string {
		$matchtabeller = (new Matchtabeller($this->tt))->matchtabeller();

		/**
		 * Bygg tabellhuvud för spikar.
		 */
		$th_rad = '';
		for ($j = 0; $j < $this->tt::TT_MAX_SPIKFÄLT; $j++) {
			$th_rad .= <<< EOT
									<th colspan="3" class="match"><input tabindex="-1" style="width: 3em;" type="number" min="0" max="13" step="1" name="tt_andel_spikar[$j]" value="{$this->tt->andel_spikar[$j]}">/{$this->tt->antal_spikar[$j]}</th>

EOT;
		}

		$scheman = new TTGridScheman();
		return <<< EOT
						<div>
							<table id="topptipstabell" class="topptipstabell">
								<tr>
									<th class="match">#</th>
									<th class="match">Match</th>
									<th class="match">E</th>
$th_rad									<th colspan="3"><span id="tt_antal_helgarderingar"></span> H | h <span id="tt_antal_halvgarderingar"></span></th>
									<th class="match">🡄</th>
									<th class="match">🞭</th>
									<th class="match">🔻</th>
								</tr>
{$matchtabeller[0]}							</table>
						</div>
{$scheman->tt_grid_scheman()}
{$this->matchdataform($matchtabeller[1])}

EOT;
	}
}
