<?php

/**
 * Klass Matchdata.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Ajax\TTAjax;

use PDO;

/**
 * Klass Matchdata.
 */
class Matchdata extends Limiter {
	/**
	 * Spara matchdata för Topptipset.
	 */
	protected function tt_matchdata(): void {
		$_REQUEST['tt_matchdata'] = is_string($_REQUEST['tt_matchdata']) ? $_REQUEST['tt_matchdata'] : '';
		parse_str($_REQUEST['tt_matchdata'], $tt_matchdata);
		if (is_array($tt_matchdata['tt_odds'])) {
			$tt_odds = array_fill(0, 8, TOM_ODDSVEKTOR);
			foreach ($tt_matchdata['tt_odds'] as $i => $odds) {
				if (is_array($odds)) {
					foreach ($odds as $j => $o) {
						$tt_odds[$i][$j] = (float) filter_var($o, FILTER_VALIDATE_FLOAT);
					}
				}
			}

			$sats = $this->db->instans->prepare("UPDATE `TT` SET `odds`=:odds");
			$sats->bindValue(':odds', implode(',', array_merge(...$tt_odds)), PDO::PARAM_STR);
			$sats->execute();
		}
	}
}
