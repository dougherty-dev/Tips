<?php

/**
 * Klass TTGridBokforing.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

use Tips\Moduler\TT;
use Tips\Egenskaper\Eka;
use Tips\Egenskaper\Ajax;

/**
 * Klass TTGridBokforing.
 * Bokför och visa datum, omgång, insats, antal rätt samt vinst.
 * Enbart momentan omgång, stöd för arkiv saknas.
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
 */
final class TTGridBokforing extends HamtaBokforing {
	use Eka;
	use Ajax;

	/**
	 * Initiera.
	 */
	public function __construct(private TT $tt) {
		parent::__construct($tt);
		$this->förgrena();
	}

	/**
	 * Bokföring av topptipsspel.
	 */
	public function tt_grid_bokföring(): string {
		$datum = date("Y-m-d H:i");
		$tabelldata = $this->tt_hämta_bokföring();
		return <<< EOT
						<table id="tt_bokföringstabell" class="omgångstabell">
							<caption>Senast spelade | Visa antal: <input id="tt_visa_antal_bokföringar" class="nummer_litet" type="number" value="{$this->tt->visa_antal_bokf}"></caption>
							<tr class="match">
								<th>#</th>
								<th>Datum</th>
								<th>Omgång</th>
								<th>Insats</th>
								<th>Rätt</th>
								<th colspan="2">Vinst</th>
							</tr>
$tabelldata
						</table>
						<form method="post" action="/#modulflikar-TT">
							<table>
								<tr class="odds">
									<td><input type="hidden" name="tt_spara_bokföring">Ny</td>
									<td><input type="text" name="datum" value="$datum"></td>
									<td><input type="text" size="5" name="omgång" value="{$this->tt->omgång}"></td>
									<td><input type="text" size="5" name="insats" value="{$this->tt->antal_rader}"></td>
									<td><button type="submit">Bokför {$this->tt->omgång}</button></td>
								</tr>
							</table>
						</form>
EOT;
	}
}
