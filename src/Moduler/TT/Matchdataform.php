<?php

/**
 * Klass Matchdataform.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

use Tips\Moduler\TT;

/**
 * Klass Matchdataform.
 */
class Matchdataform {
	/**
	 * Initiera.
	 */
	public function __construct(public TT $tt) {
	}

	/**
	 * Grid för TT-matcher.
	 */
	public function matchdataform(string $oddstabell): string {
		return <<< EOT
						<form id="tt_matchdata" method="post" action="/">
							<table class="topptipstabell">
								<tr>
									<th class="match">#</th>
									<th class="match">1</th>
									<th class="match">X</th>
									<th class="match">2</th>
									<th class="match">Match</th>
									<th colspan="3" class="odds">Odds</th>
									<th colspan="3" class="odds">Spektrum</th>
									<th colspan="3" class="streck">Streck</th>
									<th colspan="3" class="streck">Spektrum</th>
									<th colspan="4" class="match">Historik</th>
								</tr>
{$oddstabell}								<tr>
									<th colspan="17" class="ramfri vänster">
										<span id="tt_spikar_notis"></span>
										<p><button id="tt_spara_omgång" value="tt_spara_omgång">Spara</button></p>
									</th>
								</tr>
							</table>
						</form>
EOT;
	}
}
