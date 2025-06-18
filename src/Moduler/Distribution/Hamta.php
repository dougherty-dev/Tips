<?php

/**
 * Klass Hamta.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Distribution;

use PDO;
use Tips\Klasser\Utdelning;
use Tips\Klasser\Prediktioner;
use Tips\Klasser\Matcher;
use Tips\Klasser\Graf;
use Tips\Klasser\DBPreferenser;
use Tips\Egenskaper\Tick;

/**
 * Klass Hamta.
 * Hämta och sätt grundvariabler.
 */
class Hamta {
	use Tick;
	use Konstanter;

	public Graf $graf;
	public DBPreferenser $db_preferenser;
	public float $minsumma = 0;
	public float $maxsumma = 0;
	public float $oddssumma = 0; // summan av oddssannolikheter för vinnande rad
	public float $procentandel = 0; // vinnande rads procentposition i disten
	public float $minprocent; // andel av distributionen som ska täckas, fr.o.m.
	public float $maxprocent; // andel av distributionen som ska täckas, t.o.m.
	public float $grund_minprocent = self::DISTRIBUTION_GRUND_MIN_STD; // defaultvärde
	public float $grund_maxprocent = self::DISTRIBUTION_GRUND_MAX_STD; // defaultvärde
	public int $andelssumma = 0;
	public string $bildfil = ''; // filväg

	/**
	 * Initiera.
	 * Sätt en del grundvariabler.
	 */
	public function __construct(
		protected Utdelning $utdelning,
		protected Prediktioner $odds,
		protected Prediktioner $streck,
		protected Matcher $matcher
	) {
		$this->db_preferenser = new DBPreferenser($this->odds->spel->db);
		$this->graf = new Graf();
		$this->bildfil = DISTRIBUTION . "/{$this->odds->spel->filnamn}.png";
		$this->uppdatera_preferenser();
		$this->hämta_distribution();
	}

	/**
	 * Uppdatera preferenser.
	 */
	public function uppdatera_preferenser(): void {
		/**
		 * Säkerställ att attraktionsfaktor är inom tillåtna intervall.
		 */
		$this->db_preferenser->int_preferens_i_intervall(
			$this->attraktionsfaktor,
			AF_MIN,
			AF_MAX,
			AF_STD,
			'distribution.attraktionsfaktor'
		);

		/**
		 * Minprocent inom tillåtna intervall.
		 */
		$this->db_preferenser->preferens_i_intervall(
			$this->grund_minprocent,
			self::DISTRIBUTION_GRUND_MIN_MIN,
			self::DISTRIBUTION_GRUND_MIN_MAX,
			self::DISTRIBUTION_GRUND_MIN_STD,
			'distribution.grund_minprocent'
		);

		/**
		 * Maxprocent inom tillåtna intervall.
		 */
		$this->db_preferenser->preferens_i_intervall(
			$this->grund_maxprocent,
			self::DISTRIBUTION_GRUND_MAX_MIN,
			self::DISTRIBUTION_GRUND_MAX_MAX,
			self::DISTRIBUTION_GRUND_MAX_STD,
			'distribution.grund_maxprocent'
		);

		/**
		 * Min < max.
		 */
		$this->db_preferenser->komparera_preferenser(
			$this->grund_minprocent,
			$this->grund_maxprocent,
			self::DISTRIBUTION_GRUND_MIN_STD,
			self::DISTRIBUTION_GRUND_MAX_STD,
			'distribution.grund_minprocent',
			'distribution.grund_maxprocent'
		);

		/**
		 * Aktuella värden.
		 */
		$this->minprocent = $this->grund_minprocent;
		$this->maxprocent = $this->grund_maxprocent;
	}

	/**
	 * Hämta distribution.
	 */
	protected function hämta_distribution(): void {
		/**
		 * Hämta parametrar för distribution.
		 */
		$sats = $this->odds->spel->db->instans->prepare('SELECT `minsumma`, `maxsumma`,
			`minprocent`, `maxprocent`, `oddssumma`, `procentandel`, `andelssumma`
			FROM `distribution` WHERE `omgång`=:omgang
			AND `speltyp`=:speltyp AND `sekvens`=:sekvens LIMIT 1');
		$sats->bindValue(':omgang', $this->odds->spel->omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $this->odds->spel->speltyp->value, PDO::PARAM_INT);
		$sats->bindValue(':sekvens', $this->odds->spel->sekvens, PDO::PARAM_INT);
		$sats->bindColumn('andelssumma', $this->andelssumma, PDO::PARAM_INT);
		$sats->execute();

		/**
		 * Populera parametrar.
		 * Säkerställ att rad faktiskt finns.
		 */
		$rad = $sats->fetchAll(PDO::FETCH_ASSOC);
		if (isset($rad[0])) {
			$rad = $rad[0];
			$this->minsumma = (float) $rad['minsumma'];
			$this->maxsumma = (float) $rad['maxsumma'];
			$this->minprocent = (float) $rad['minprocent'];
			$this->maxprocent = (float) $rad['maxprocent'];
			$this->oddssumma = (float) $rad['oddssumma'];
			$this->procentandel = $rad['procentandel'];
		}
	}
}
