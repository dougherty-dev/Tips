<?php

/**
 * Klass GridResultatVinstrader.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Omgang;

/**
 * Klass GridResultatVinstrader.
 */
class GridResultatVinstrader extends GridResultatModuler {
	/**
	 * Visa eventuella vinstrader.
	 */
	protected function vinstrader(): string {
		if (!$this->spelad) {
			return '';
		}

		$vinstrader = t(7, "<code><strong>{$this->tips->utdelning->tipsrad}</strong></code><br>");

		/**
		 * Iterera över spelade tipsrader.
		 */
		foreach ($this->tips->spelade->tipsvektor as $index => $tipsvektor) {
			$rad = '';
			foreach (str_split(siffror_till_symboler($tipsvektor)) as $k => $rad_1x2) {
				$rad .= $rad_1x2 === $this->tips->utdelning->tipsrad[$k] ?
					$rad_1x2 : '<span class="ickevinst">' . $rad_1x2 . '</span>';
			}

			$rätt = antal_rätt($tipsvektor, $this->tips->utdelning->tipsrad_012);
			$this->rättvektor[$rätt]++;

			/**
			 * Välj ut rader med antal rätt i vinstgrupp 10–13.
			 */
			if ($rätt >= 10) {
				$this->vinst += $this->tips->utdelning->utdelning[MATCHANTAL - $rätt];
				$stil = match ($rätt) {
					13 => ['<strong><span class="vinstrad">', '</span></strong>'],
					12 => ['<strong>', '</strong>'],
					11 => ['<em>', '</em>'],
					default => ['', '']
				};

				$vinstrader .= t(7, "<code>{$stil[0]}$rad ($rätt r) (# $index){$stil[1]}</code><br>");
			}
		}

		return $vinstrader;
	}
}
