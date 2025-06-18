<?php

/**
 * Klass Utdelning.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

use PDO;
use Tips\Klasser\Utdelning\Utdelningsdata;

/**
 * Klass Utdelning.
 * Utdelning samt antal vinnare i respektive vinstgrupp.
 * Även datum och tipsrad.
 */
final class Utdelning extends Utdelningsdata {
	/**
	 * Initiera.
	 */
	public function __construct(public Spel $spel) {
		parent::__construct($spel);
		$this->hämta_utdelning();
	}

	/**
	 * Hämta utdelning.
	 */
	public function hämta_utdelning(): void {
		$sats = $this->spel->db->instans->prepare("SELECT `år`, `vecka`, `tipsrad_012`,
			`u13`, `u12`, `u11`, `u10`, `a13`, `a12`, `a11`, `a10`
			FROM `utdelning` WHERE `omgång`=:omgang AND `speltyp`=:speltyp LIMIT 1");
		$sats->bindValue(':omgang', $this->spel->omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $this->spel->speltyp->value, PDO::PARAM_INT);
		$sats->bindColumn('år', $this->år, PDO::PARAM_INT);
		$sats->bindColumn('vecka', $this->vecka, PDO::PARAM_INT);
		$sats->bindColumn('tipsrad_012', $this->tipsrad_012, PDO::PARAM_STR);

		/**
		 * Populera utdelning.
		 */
		$vektor = ['u13', 'u12', 'u11', 'u10'];
		array_map(
			fn ($index): bool =>
			$sats->bindColumn($vektor[$index], $this->utdelning[$index], PDO::PARAM_INT),
			array_keys($vektor)
		);

		/**
		 * Populera vinnare.
		 */
		$vektor = ['a13', 'a12', 'a11', 'a10'];
		array_map(
			fn ($index): bool =>
			$sats->bindColumn($vektor[$index], $this->vinnare[$index], PDO::PARAM_INT),
			array_keys($vektor)
		);

		$sats->execute();
		if ($sats->fetch(PDO::FETCH_ASSOC) !== false) {
			$this->tipsrad = siffror_till_symboler($this->tipsrad_012);
		}

		$this->har_tipsrad = ($this->tipsrad_012 !== '');
	}

	/**
	 * Spara utdelning.
	 */
	public function spara_utdelning(): void {
		$sats = $this->spel->db->instans->prepare("REPLACE INTO `utdelning`
			(`omgång`, `speltyp`, `år`, `vecka`, `tipsrad_012`,
			`u13`, `u12`, `u11`, `u10`, `a13`, `a12`, `a11`, `a10`)
			VALUES (:omgang, :speltyp, :ar, :vecka, :tipsrad_012,
			:u13, :u12, :u11, :u10, :a13, :a12, :a11, :a10)");
		$sats->bindValue(':omgang', $this->spel->omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $this->spel->speltyp->value, PDO::PARAM_INT);
		$sats->bindValue(':ar', $this->år, PDO::PARAM_INT);
		$sats->bindValue(':vecka', $this->vecka, PDO::PARAM_INT);
		$sats->bindValue(':tipsrad_012', $this->tipsrad_012, PDO::PARAM_STR);

		/**
		 * Populera utdelning.
		 */
		$vektor = ['u13', 'u12', 'u11', 'u10'];
		array_map(
			fn ($index): bool =>
			$sats->bindValue($vektor[$index], $this->utdelning[$index], PDO::PARAM_INT),
			array_keys($vektor)
		);

		/**
		 * Populera vinnare.
		 */
		$vektor = ['a13', 'a12', 'a11', 'a10'];
		array_map(
			fn ($index): bool =>
			$sats->bindValue($vektor[$index], $this->vinnare[$index], PDO::PARAM_INT),
			array_keys($vektor)
		);

		/**
		 * Exekvera och logga.
		 */
		$kommentar = match ($sats->execute()) {
			true => ": ✅ Sparade utdelning.",
			false => ": ❌ Kunde inte spara utdelning."
		};
		$this->spel->db->logg->logga(self::class . "$kommentar ({$this->spel->omgång})");
	}
}
