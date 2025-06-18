<?php

/**
 * Klass Omgang.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\FANN;

/**
 * Klass Omgang.
 */
class Omgang extends Matchtabell {
	/**
	 * Omgångsdata för modul.
	 */
	protected function fannomgång(): string {
		$matchtabell = '';

		foreach (array_keys($this->odds->sannolikheter) as $index) {
			$vinsttecken = $this->utdelning->har_tipsrad ? $this->utdelning->tipsrad[$index] : '';
			$fanntecken = siffror_till_symboler($this->utdata[$index]);

			/**
			 * Markera med färg om vinst eller förlust.
			 */
			$fannstil = match (true) {
				!$this->utdelning->har_tipsrad => ' class="center"',
				str_contains($fanntecken, $this->utdelning->tipsrad[$index]) => ' class="vinst center"',
				default => ' class="storförlust center"',
			};

			$matchtabell .= $this->matchtabell($index, $fannstil, $fanntecken, $vinsttecken);
		}

		/**
		 * Eka ut data.
		 */
		return <<< EOT
						<p><select id="fann_min">
{$this->generatorsträng(7)}						</select></p>
						<table id="fanntabell">
							<tr>
								<th class="match">#</th>
								<th colspan="2" class="match mindre">FANN/{$this->fann_rätt}</th>
								<th class="match">Rad</th>
								<th colspan="2" class="match">Match</th>
								<th class="match">Res</th>
								<th colspan="3" class="odds">Odds</th>
								<th colspan="3" class="odds">Justerade</th>
								<th colspan="3" class="streck">Streck</th>
								<th colspan="3" class="streck">Justerade</th>
								<th colspan="4" class="match">Historik</th>
							</tr>
$matchtabell						</table>
EOT;
	}
}
