<?php

/**
 * Klass Hamta.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Prediktioner;

use PDO;
use Tips\Klasser\Spel;

/**
 * Klass Hamta.
 */
class Hamta {
	public bool $komplett = false;
	/** @var array<int, float[]> $prediktioner */ public array $prediktioner;

	public function __construct(public Spel $spel, public string $tabell) {
	}

	/**
	 * Hämta prediktioner för enskild omgång.
	 */
	protected function hämta_prediktioner(): void {
		$this->prediktioner = TOM_ODDSMATRIS;
		$sats = $this->spel->db->instans->prepare("SELECT *
			FROM `{$this->tabell}` WHERE `omgång`=:omgang AND `speltyp`=:speltyp LIMIT 1");
		$sats->bindValue(':omgang', $this->spel->omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $this->spel->speltyp->value, PDO::PARAM_INT);
		$sats->execute();
		foreach ($sats->fetchAll(PDO::FETCH_ASSOC) as $rad) {
			$this->komplett = (bool) $rad['komplett'];
			$this->prediktioner = array_chunk(array_map(fn (int $index): float => $rad["p$index"], PLATT_ODDSMATRIS), 3);
		}
	}
}
