<?php

/**
 * Klass GridOmgang.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Omgang;

use PDO;
use Tips\Klasser\Tips;

/**
 * Klass GridOmgang.
 * Välj omgångsnummer medelst rullgardinsmeny, pilar eller manuell inmatning.
 */
final class GridOmgang {
	/**
	 * Init.
	 */
	public function __construct(private Tips $tips) {
	}

	/**
	 * Visa omgång.
	 */
	public function visa(): string {
		/**
		 * Hämta antal kompletta omgångar.
		 */
		$antal_kompletta = $this->antal_kompletta();

		/**
		 * Initialisera variabler.
		 */
		$omgångar = []; // håller alla omgångar
		$grid_omgång = ''; // malltext
		$antal_omgångar = $omgång = 0;

		/**
		 * Hämta alla omgångar från databas.
		 * Inkluderar inkompletta.
		 */
		$sats = $this->tips->spel->db->instans->prepare("SELECT `omgång` FROM `matcher`
				WHERE `speltyp`={$this->tips->spel->speltyp->value} ORDER BY `omgång` DESC");
		$sats->execute();
		$sats->bindColumn('omgång', $omgång, PDO::PARAM_INT);

		/**
		 * Bilda meny.
		 */
		while ($sats->fetch(PDO::FETCH_OBJ)) {
			$antal_omgångar++;
			$vald = $this->tips->spel->omgång === $omgång ? ' selected="selected"' : '';
			$grid_omgång .= t(8, "<option$vald>$omgång</option>"); // bygg meny
			$omgångar[] = $omgång;
		}

		/**
		 * Bilda navigeringspilar.
		 */
		$index = (int) array_search($this->tips->spel->omgång, $omgångar, true);
		$föregående = strval($omgångar[$index + 1] ?? '');
		$nästa = strval($omgångar[$index - 1] ?? '');

		/**
		 * Eka ut resultatet i HTML.
		 */
		return (new RenderaOmgang($this->tips))->rendera_html($grid_omgång, $föregående, $nästa, $antal_kompletta, $antal_omgångar);
	}

	/**
	 * Hämta antal kompletta.
	 */
	private function antal_kompletta(): int {
		/**
		 * Hämta antal omgångar från databas.
		 * Enbart omgångar med kompletta odds och resultat.
		 */
		$sats = $this->tips->spel->db->instans->prepare("SELECT COUNT(`odds`.`omgång`) FROM `odds`
			LEFT JOIN `streck` ON `odds`.`omgång`=`streck`.`omgång`
			LEFT JOIN `matcher` ON `odds`.`omgång`=`matcher`.`omgång`
			WHERE `odds`.`speltyp`={$this->tips->spel->speltyp->value} AND
			`odds`.`komplett` AND `streck`.`komplett` AND `matcher`.`komplett`");
		$sats->execute();
		$antal_kompletta = (int) $sats->fetchColumn();
		$sats->closeCursor();

		/**
		 * Returnera.
		 */
		return $antal_kompletta;
	}
}
