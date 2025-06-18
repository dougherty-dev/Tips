<?php

/**
 * Klass HamtaBokforing.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

use PDO;
use Tips\Moduler\TT;

/**
 * Klass HamtaBokforing.
 * Databasrutiner för att hämta och spara bokföring.
 */
class HamtaBokforing {
	/**
	 * Initiera.
	 */
	public function __construct(private TT $tt) {
	}

	/**
	 * Hämta bokföring.
	 */
	protected function tt_hämta_bokföring(): string {
		$tabelldata = '';
		$sats = $this->tt->utdelning->spel->db->instans->prepare("SELECT * FROM
			(SELECT * FROM `TT_bokföring` ORDER BY `id` DESC LIMIT {$this->tt->visa_antal_bokf})
			AS `ordnad` ORDER BY `id` ASC");
		$sats->execute();

		/**
		 * Hämta enskilda poster i bokföring.
		 * Visar 20 senaste som default.
		 */
		foreach ($sats->fetchAll(PDO::FETCH_ASSOC) as $r) {
			$tabelldata .= <<< EOT
								<tr data-tt-bokföring-id="{$r['id']}">
									<td>{$r['id']}</td>
									<td>{$r['datum']}</td>
									<td>{$r['omgång']}</td>
									<td>{$r['insats']}</td>
									<td><input type="text" size="5" name="rätt" value="{$r['rätt']}"></td>
									<td><input type="text" size="5" name="vinst" value="{$r['vinst']}"></td>
									<td class="tt_radera_bokföring pekare">❌</td>
								</tr>
EOT;
		}

		return $tabelldata;
	}

	/**
	 * Spara bokföring.
	 * js: tt_spara_bokföring
	 */
	protected function tt_spara_bokföring(): void {
		$sats = $this->tt->utdelning->spel->db->instans->prepare("INSERT INTO `TT_bokföring`
			(`datum`, `omgång`, `insats`) VALUES (:datum, :omgang, :insats)");
		$sats->bindValue(':datum', $_REQUEST['datum'], PDO::PARAM_STR);
		$sats->bindValue(':omgang', $this->tt->omgång, PDO::PARAM_INT);
		$sats->bindValue(':insats', $_REQUEST['insats'], PDO::PARAM_INT);
		$kommentar = match ($sats->execute()) {
			true => ": ✅ Tillade TT-bokföring.",
			false => ": ❌ Kunde inte tillägga TT-bokföring."
		};

		$this->tt->utdelning->spel->db->logg->logga(self::class . "$kommentar ({$this->tt->omgång})");
	}
}
