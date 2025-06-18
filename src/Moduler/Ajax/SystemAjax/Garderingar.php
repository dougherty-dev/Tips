<?php

/**
 * Klass Garderingar.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Ajax\SystemAjax;

use PDO;
use Tips\Klasser\Spel;
use Tips\Klasser\DBPreferenser;
use Tips\Moduler\System\Konstanter;

/**
 * Klass Garderingar.
 */
class Garderingar {
	use Konstanter;

	protected Spel $spel;
	protected DBPreferenser $db_preferenser;

	/**
	 * Initiera.
	 */
	public function __construct() {
		$this->spel = new Spel();
		$this->db_preferenser = new DBPreferenser($this->spel->db);
	}

	/**
	 * Spara garderingar.
	 * Manuella garderingar som komplement till R-system.
	 */
	protected function garderingar(): void {
		$antal_garderingar = array_fill(0, self::SYSTEM_MAX_ANTAL_FÄLT, 0);

		$garderingar = $this->parse($_REQUEST['garderingar']);
		$andel_garderingar = $this->parse($_REQUEST['andel_garderingar']);

		/**
		 * Filtrera indata samt uppdatera andelar.
		 */
		foreach ($garderingar as $index => $gardering) {
			$gardering = (array) filter_var_array((array) $gardering, FILTER_SANITIZE_SPECIAL_CHARS);
			$garderingar[$index] = $gardering;

			/**
			 * Räkna upp antal om gardering finns.
			 */
			foreach ($gardering as $gard) {
				if ($gard != TOM_STRÄNGVEKTOR) {
					$antal_garderingar[$index]++;
				}
			}

			$andel_garderingar[$index] = min($andel_garderingar[$index], $antal_garderingar[$index]);
		}

		/**
		 * Spara garderingar.
		 */
		$sats = $this->spel->db->instans->prepare("REPLACE INTO `system`
			(`omgång`, `speltyp`, `sekvens`, `garderingar`, `andel_garderingar`)
			VALUES (:omgang, :speltyp, :sekvens, :garderingar, :andel_garderingar)");
		$sats->bindValue(':omgang', $this->spel->omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $this->spel->speltyp->value, PDO::PARAM_INT);
		$sats->bindValue(':sekvens', $this->spel->sekvens, PDO::PARAM_INT);
		$sats->bindValue(':garderingar', implode(',', array_merge([], ...array_merge([], ...$garderingar))), PDO::PARAM_STR);
		$sats->bindValue(':andel_garderingar', implode(',', $andel_garderingar), PDO::PARAM_STR);
		echo $sats->execute();
	}

	/**
	 * Hantera extrahering av post-data.
	 * @return mixed[]
	 */
	private function parse(mixed $request): array {
		$request = is_string($request) ? $request : '';
		parse_str($request, $resultat);
		return (array) array_values($resultat)[0];
	}
}
