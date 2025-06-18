<?php

/**
 * Klass Toppmeny.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

/**
 * Klass Toppmeny.
 */
class Toppmeny {
	public function toppmeny(string $typsträng): string {
		/**
		 * Skicka tabell.
		 */
		return <<< EOT
						<table>
							<tr>
								<td>
									<a href="/"><div class="logotyp topptipset">
										<img src="/img/ss.svg" height="30" class="ss-logo" alt="Svenska spel">
										<img src="/img/topptipset.svg" height="45" alt="topptipset">
									</div></a>
								</td>
								<td>
									<form id="hämta_topptips" method="post" action="/#modulflikar-TT">
										<select id="topptipstyp">
$typsträng										</select>
										<button name="hämta_topptips">Hämta TT-data</button>
									</form>
								</td>
							</tr>
						</table>
EOT;
	}
}
