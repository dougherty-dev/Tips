<?php

/**
 * Klass Annonsera.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Moduler;

use ReflectionClass;
use Tips\Klasser\Utdelning;
use Tips\Klasser\Prediktioner;
use Tips\Klasser\Matcher;

/**
 * Klass Annonsera.
 */
class Annonsera {
	/** @var object[] $m_moduler */ public array $m_moduler = [];
	/** @var array<string, array<int|string, string>> $moduldata */ public array $moduldata = [];

	public function __construct(
		public Utdelning $utdelning,
		public Prediktioner $odds,
		public Prediktioner $streck,
		public Matcher $matcher
	) {
	}

	/**
	 * Annonsera aktiva moduler.
	 */
	public function annonsera_moduler(): void {
		$this->moduldata = [];
		foreach ($this->m_moduler as $m) {
			if (method_exists($m, 'pröva_tipsrad')) {
				$ok = ($this->utdelning->tipsrad_012 === '') ? '🔅' : ($m->pröva_tipsrad($this->utdelning->tipsrad_012) ? '✅' : '❌');
				$annons = method_exists($m, 'annonsera') ? $m->annonsera() : '';
				$klass = (new ReflectionClass($m))->getShortName();
				$this->moduldata[$klass] = [$ok, $annons];
			}
		}
	}
}
