<?php

/**
 * Klass GridOmgang.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Omgang;

use PDO;
use Tips\Klasser\Tips;
use Tips\Egenskaper\Eka;

/**
 * Klass GridOmgang.
 * Välj omgångsnummer medelst rullgardinsmeny, pilar eller manuell inmatning.
 */
final class GridOmgang {
	use Eka;

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
		 * Hämta antal omgångar från databas.
		 * Enbart omgångar med kompletta odds och resultat.
		 */
		$sats = $this->tips->spel->db->instans->prepare("SELECT COUNT(`odds`.`omgång`) FROM `odds`
			LEFT JOIN `streck` ON `odds`.`omgång`=`streck`.`omgång`
			LEFT JOIN `matcher` ON `odds`.`omgång`=`matcher`.`omgång`
			WHERE `odds`.`speltyp`={$this->tips->spel->speltyp->value} AND
			`odds`.`komplett` AND `streck`.`komplett` AND `matcher`.`komplett`");
		$sats->execute();
		$antal_kompletta = $sats->fetchColumn();
		$sats->closeCursor();

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
		$föregående = $omgångar[$index + 1] ?? '';
		$nästa = $omgångar[$index - 1] ?? '';

		/**
		 * Eka ut resultatet i HTML.
		 */
		return <<< EOT
						<a href="/"><div class="logotyp {$this->tips->spel->speltyp->produktnamn()}">
							<img src="/img/ss.svg" height="30" class="ss-logo" alt="Svenska spel">
							<img src="/img/{$this->tips->spel->speltyp->produktnamn()}.svg" height="45" alt="{$this->tips->spel->speltyp->produktnamn()}">
						</div></a>
						<form id="manuell">
							<span style="font-size: 2em; vertical-align: middle;">{$this->eka($this->tips->spel->komplett ? '✅' : '❌')}</span>
							<select id="genererad_omgång">
$grid_omgång							</select>
							<input type="text" autocomplete="off" id="manuell_omgång" size="6" value="">
							<input type="submit" value="Manuell"><br>
						</form>
						<button{$this->eka($föregående ? '' : ' disabled="disabled"')} id="föregående" value="$föregående">⇦</button>
						<button{$this->eka($nästa ? '' : ' disabled="disabled"')} id="nästa" value="$nästa">⇨</button>
						($antal_kompletta / $antal_omgångar)
EOT;
	}
}
