<?php

/**
 * Klass Tips.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

/**
 * Klass Tips.
 */
final class Tips {
	public Utdelning $utdelning;
	public Matcher $matcher;
	public Prediktioner $odds;
	public Prediktioner $streck;
	public Parallellisering $parallellisering;
	public Spelade $spelade;
	public Moduler $moduler;

	/**
	 * Instantiera en rad klasser.
	 */
	public function __construct(public Spel $spel) {
		$this->utdelning = new Utdelning($this->spel);
		$this->matcher = new Matcher($this->spel);
		$this->odds = new Prediktioner($this->spel, 'odds');
		$this->streck = new Prediktioner($this->spel, 'streck');
		$this->parallellisering = new Parallellisering($this->spel);
		$this->spelade = new Spelade($this->utdelning, $this->matcher);
		$this->moduler = new Moduler($this->utdelning, $this->odds, $this->streck, $this->matcher);
	}

	/**
	 * Spara tipsdata.
	 */
	public function spara_tips(): void {
		$this->utdelning->spara_utdelning();
		$this->matcher->spara_matcher();
		$this->odds->spara_prediktioner();
		$this->streck->spara_prediktioner();
		$this->odds->justerade_pred->spara_historik();
		$this->spel->db->logg->logga(self::class . ": ✅ Sparade tips. ({$this->spel->omgång})");
	}
}
