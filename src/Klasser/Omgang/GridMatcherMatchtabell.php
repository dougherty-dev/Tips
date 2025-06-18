<?php

/**
 * Klass GridMatcherMatchtabell.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Omgang;

use Tips\Klasser\Tips;
use Tips\Egenskaper\Eka;

/**
 * Klass GridMatcherMatchtabell.
 */
class GridMatcherMatchtabell {
	use Eka;

	public function __construct(protected Tips $tips) {
	}

	/**
	 * Visa matcher.
	 * @param array<string, int> $rätt
	 */
	protected function matchtabell(array $rätt, string $grid_matcher): string {
		return <<< EOT
							<table id="matchtabell">
								<tr>
									<th class="match">#</th>
									<th class="match">1</th>
									<th class="match">X</th>
									<th class="match">2</th>
									<th colspan="3" class="match">{$this->eka($this->tips->matcher->komplett ? '✅' : '❌')} Match</th>
									<th colspan="2" class="match">Res</th>
									<th colspan="3" class="odds">{$this->eka($this->tips->odds->komplett ? '✅' : '❌')} Odds ({$rätt['odds']} r)</th>
									<th colspan="3" class="odds">Spektrum</th>
									<th colspan="3" class="streck">{$this->eka($this->tips->streck->komplett ? '✅' : '❌')} Streck ({$rätt['streck']} r)</th>
									<th colspan="3" class="streck">Spektrum</th>
									<th colspan="3" class="streck">Streckodds</th>
									<th class="streck">♻️</th>
								</tr>
$grid_matcher							</table>
EOT;
	}
}
