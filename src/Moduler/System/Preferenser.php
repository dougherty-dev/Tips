<?php

/**
 * Klass Preferenser.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\System;

use Tips\Klasser\Tips;
use Tips\Klasser\Utdelning;
use Tips\Klasser\Prediktioner;
use Tips\Klasser\Matcher;
use Tips\Klasser\DBPreferenser;
use Tips\Egenskaper\Tick;

/**
 * Klass Preferenser.
 */
class Preferenser {
	use Tick;
	use Konstanter;

	public RKod $kod;
	public DBPreferenser $db_preferenser;
	/**
	 * @var int[] $antal_garderingar
	 */
	public array $antal_garderingar;
	/**
	 * @var int[] $andel_garderingar
	 */
	public array $andel_garderingar;
	/**
	 * @var array<int, array<int, string[]>> $garderingar
	 */
	public array $garderingar;
	public bool $pröva_garderingar;
	public bool $pröva_reduktion;

	public function __construct(
		protected Utdelning $utdelning,
		protected Prediktioner $odds,
		protected Prediktioner $streck,
		protected Matcher $matcher
	) {
	}

	/**
	 * Uppdatera preferenser.
	 */
	protected function uppdatera_preferenser(): void {
		$this->db_preferenser = new DBPreferenser($this->odds->spel->db);

		$this->garderingar = array_fill(
			0,
			self::SYSTEM_MAX_ANTAL_FÄLT,
			array_fill(0, MATCHANTAL, TOM_STRÄNGVEKTOR)
		);
		$this->antal_garderingar = array_fill(0, self::SYSTEM_MAX_ANTAL_FÄLT, 0);
		$this->andel_garderingar = $this->antal_garderingar;

		$this->kod = RKod::cases()[0];
		$kod = RKod::tryFrom($this->db_preferenser->hämta_preferens('system.kod'));
		match ($kod instanceof RKod) {
			true => $this->kod = $kod,
			false => $this->db_preferenser->spara_preferens('system.kod', $this->kod->name)
		};

		$this->db_preferenser->int_preferens_i_intervall(
			$this->attraktionsfaktor,
			AF_MIN,
			AF_MAX,
			AF_STD,
			'system.attraktionsfaktor'
		);

		$this->pröva_garderingar = (bool) $this->db_preferenser->hämta_preferens('system.pröva_garderingar');
		$this->pröva_reduktion = (bool) $this->db_preferenser->hämta_preferens('system.pröva_reduktion');
	}
}
