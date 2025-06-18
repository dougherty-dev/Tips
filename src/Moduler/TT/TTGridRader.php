<?php

/**
 * Klass TTGridRader.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

use Tips\Moduler\TT;

/**
 * Klass TTGridRader.
 */
final class TTGridRader {
	/**
	 * Injekta Topptipset.
	 */
	public function __construct(private TT $tt) {
	}

	/**
	 * Visa rader för Topptipset.
	 */
	public function tt_grid_rader(): string {
		$senaste_tipsrader = file_get_contents($this->tt::TT_TEXTFIL);
		$genererade_rader = $this->tt->db_preferenser->hämta_preferens('topptips.genererade_rader');
		return <<< EOT
						<p>Senaste rader ({$this->tt->antal_rader} / $genererade_rader):</p>
						<p><code>$senaste_tipsrader						</code></p>
						<p>{$this->tt->tipsgraf}</p>
EOT;
	}

	/**
	 * Visa statistik för Topptipset.
	 */
	public function tt_grid_statistik(): string {
		return <<< EOT
						<table>
							<tr>
								<th>Odds 1 2 3</th>
								<th>Odds 1 2</th>
								<th>Odds r (<input tabindex="-1" id="tt_odds_rätt_min" type="number" min="0" max="8" value="{$this->tt->odds_rätt_min}">–<input tabindex="-1" id="tt_odds_rätt_max" type="number" min="0" max="8" value="{$this->tt->odds_rätt_max}">)</th>
								<th>Ant 1 (<input tabindex="-1" id="tt_antal_1_min" type="number" min="0" max="8" value="{$this->tt->antal_1_min}">–<input tabindex="-1" id="tt_antal_1_max" type="number" min="0" max="8" value="{$this->tt->antal_1_max}">)</th>
								<th>Ant X (<input tabindex="-1" id="tt_antal_X_min" type="number" min="0" max="8" value="{$this->tt->antal_X_min}">–<input tabindex="-1" id="tt_antal_X_max" type="number" min="0" max="8" value="{$this->tt->antal_X_max}">)</th>
								<th>Ant 2 (<input tabindex="-1" id="tt_antal_2_min" type="number" min="0" max="8" value="{$this->tt->antal_2_min}">–<input tabindex="-1" id="tt_antal_2_max" type="number" min="0" max="8" value="{$this->tt->antal_2_max}">)</th>
							</tr>
							<tr><td>3: 413 / 1443 = 28.6 %</td><td>2: 662</td><td>8r: 11 / 1443 = 0.76</td><td>8: 3 / 1443 = 0.21</td><td>8: 0</td><td>8: 0</td></tr><tr><td>2: 623 / 1443 = 36.2 %</td><td>1: 616</td><td>7r: 64 / 1443 = 4.43</td><td>7: 16 / 1443 = 1.11</td><td>7: 0</td><td>7: 1 / 1443 = 0.069</td></tr><tr><td>1: 340 / 1443 = 23.6 %</td><td>0: 144</td><td>6r: 199 / 1443 = 13.79</td><td>6: 84 / 1443 = 5.82</td><td>6: 7 / 1443 = 0.49</td><td>6: 21 / 1443 = 1.46</td></tr><tr><td>0: 67 / 1443 = 4.6 %</td><td></td><td>5r: 341 / 1443 = 23.63</td><td>5: 223 / 1443 = 15.45</td><td>5: 36 / 1443 = 2.49</td><td>5: 90 / 1443 = 6.24</td></tr><tr><td>1 + 2 : 963 / 1443 = 66.7 %</td><td></td><td>4r: 331 / 1443 = 22.94</td><td>4: 339 / 1443 = 23.49</td><td>4: 146 / 1443 = 10.12</td><td>4: 241 / 1443 = 16.70</td></tr><tr><td>(1–2, 88.4 %)</td><td>(0–1, 88.5 %)</td><td>3r: 323 / 1443 = 22.38</td><td>3: 375 / 1443 = 25.99</td><td>3: 289 / 1443 = 20.03</td><td>3: 356 / 1443 = 24.67</td></tr><tr><td></td><td></td><td>2r: 135 / 1443 = 9.36</td><td>2: 295 / 1443 = 20.44</td><td>2: 428 / 1443 = 29.66</td><td>2: 413 / 1443 = 28.62</td></tr><tr><td></td><td></td><td>1r: 32 / 1443 = 2.22</td><td>1: 97 / 1443 = 6.72</td><td>1: 391 / 1443 = 27.10</td><td>1: 260 / 1443 = 18.02</td></tr><tr><td></td><td></td><td>0r: 7 / 1443 = 0.49</td><td>0: 11 / 1443 = 0.76</td><td>0: 146 / 1443 = 10.12</td><td>0: 61 / 1443 = 0.04</td></tr>
						</table>
EOT;
	}
}
