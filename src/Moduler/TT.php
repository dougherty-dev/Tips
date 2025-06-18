<?php

/**
 * Klass TT.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler;

use Tips\Klasser\Utdelning;
use Tips\Klasser\Prediktioner;
use Tips\Klasser\Matcher;
use Tips\Klasser\DBPreferenser;
use Tips\Moduler\TT\TTGridGenerera\Generera;
use Tips\Moduler\TT\TTHamtaTopptips;
use Tips\Moduler\TT\TTHistorik;
use Tips\Moduler\TT\Berakna;
use Tips\Moduler\TT\Visa;

/**
 * Klass TT.
 * Hantera Topptipset som en modul.
 * För hög kopplingsfaktor.
 */
final class TT extends Berakna {
	public TTHistorik $historik;
	/**
	 * @var array<int, float[]> $utfallshistorik
	 */
	public array $utfallshistorik;
	/**
	 * @var string[] $rader
	 */
	public array $rader = [];

	/**
	 * Init. Uppdatera konstruktor.
	 */
	public function __construct(
		public Utdelning $utdelning,
		public Prediktioner $odds,
		public Prediktioner $streck,
		public Matcher $matcher
	) {
		parent::__construct($utdelning, $odds, $streck, $matcher);
		$this->populera();
	}

	/**
	 * Boota upp systemet.
	 */
	private function populera(): void {
		$this->db_preferenser = new DBPreferenser($this->utdelning->spel->db);
		$this->initiera();
		$this->historik = new TTHistorik($this);
		$this->uppdatera_preferenser();
		$this->hämta_data();
		$this->hämta_spikar();
		new Generera($this);
		new TTHamtaTopptips($this);
		$this->beräkna_sannolikheter();
	}

	/**
	 * Visa modul (omdirigering)
	 */
	public function visa_modul(): void {
		(new Visa($this))->visa();
	}
}
