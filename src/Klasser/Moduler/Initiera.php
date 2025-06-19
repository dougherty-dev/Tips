<?php

/**
 * Klass Initiera.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Moduler;

use ReflectionClass;
use PDO;
use PDOStatement;
use Tips\Klasser\Utdelning;
use Tips\Klasser\Prediktioner;
use Tips\Klasser\Matcher;

/**
 * Klass Initiera.
 */
class Initiera extends Kontrollera {
	/** @var array<int, array<int|string, int>> $moduler */ public array $moduler = [];
	/** @var string[] $aktiva_moduler */ public array $aktiva_moduler = [];
	/** @var string[] $skarpa_moduler */ public array $skarpa_moduler = [];

	/**
	 * Omdefiniera konstruktorn.
	 */
	public function __construct(
		public Utdelning $utdelning,
		public Prediktioner $odds,
		public Prediktioner $streck,
		public Matcher $matcher
	) {
		parent::__construct($utdelning, $odds, $streck, $matcher);
		$this->initiera_moduler();
	}

	/**
	 * Hänta och instantiera aktiva moduler.
	 */
	public function initiera_moduler(): void {
		$this->kontrollera_nya_moduler();
		$this->moduler = $this->aktiva_moduler = $this->skarpa_moduler = $this->m_moduler = [];

		$index = 1;
		/** @var PDOStatement $sats */
		$sats = $this->utdelning->spel->db->instans->query('SELECT * FROM `moduler` ORDER BY `prioritet`');
		foreach ($sats->fetchAll(PDO::FETCH_ASSOC) as $rad) {
			$this->moduler[] = [$index++, $rad['namn'], $rad['aktiv']];
			if ($rad['aktiv']) {
				$this->aktiva_moduler[] = $rad['namn'];
			}
		}

		foreach ($this->aktiva_moduler as $modul) {
			$class = '\\Tips\\Moduler\\' . $modul;
			$m_modul = new $class($this->utdelning, $this->odds, $this->streck, $this->matcher);
			$this->m_moduler[] = $m_modul;

			if (method_exists($m_modul, 'pröva_tipsrad')) {
				$this->skarpa_moduler[] = $modul;
			}
		}

		$this->annonsera_moduler();
	}
}
