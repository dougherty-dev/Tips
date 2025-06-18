<?php

/**
 * Klass Omgang.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\HG;

/**
 * Klass Omgang.
 */
class Omgang extends Matchtabell {
	/**
	 * Visa omgångsdata för HG-modul.
	 */
	protected function hg_omgång(): string {
		$matchtabell = '';

		foreach (array_keys($this->odds->sannolikheter) as $index) {
			$vinsttecken = $this->utdelning->tipsrad ? $this->utdelning->tipsrad[$index] : '';
			$hg_tecken = siffror_till_symboler($this->utdata[$index]);

			/**
			 * Visa vinnande matcher om omgången är avgjord.
			 */
			$hgstil = match (true) {
				!$this->utdelning->har_tipsrad => '',
				str_contains($hg_tecken, $this->utdelning->tipsrad[$index]) => 'vinst ',
				default => 'storförlust ',
			};

			$matchtabell .= $this->matchtabell($index, $hgstil, $hg_tecken, $vinsttecken);
		}

		return <<< EOT
						<table id="hgtabell">
							<tr>
								<th class="match">#</th>
								<th class="match mindre">HG/{$this->hg_rätt}</th>
								<th class="match">Rad</th>
								<th colspan="2" class="match">Match</th>
								<th class="match">Res</th>
								<th colspan="3" class="odds">Odds</th>
								<th colspan="3" class="odds">Justerade</th>
								<th colspan="4" class="match">Historik</th>
							</tr>
$matchtabell						</table>
EOT;
	}
}
