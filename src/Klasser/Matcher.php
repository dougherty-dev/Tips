<?php

/**
 * Klass Matcher.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

use PDO;

/**
 * Klass Matcher.
 * Hämta och spara matchdata.
 */
final class Matcher {
	public string $spelstopp = '';
	public bool $komplett = false;
	/**
	 * @var string[] $match
	 */
	public array $match;
	/**
	 * @var int[] $matchstatus
	 */
	public array $matchstatus;
	/**
	 * @var string[] $resultat
	 */
	public array $resultat;

	/**
	 * Init.
	 */
	public function __construct(public Spel $spel) {
		$this->hämta_matcher();
	}

	/**
	 * Hämta enskild match från databas.
	 */
	public function hämta_matcher(): void {
		/**
		 * Initiera tomma fält.
		 */
		$this->match = TOMRAD;
		$this->resultat = TOMRAD;
		$this->matchstatus = NOLLRAD;

		/**
		 * Hämta matcher från aktuell omgång.
		 */
		$sats = $this->spel->db->instans->prepare('SELECT *
			FROM `matcher` WHERE `omgång`=:omgang AND `speltyp`=:speltyp LIMIT 1');
		$sats->bindValue(':omgang', $this->spel->omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $this->spel->speltyp->value, PDO::PARAM_INT);
		$sats->execute();

		/**
		 * Iterera över matchkolumner.
		 */
		$rad = $sats->fetchAll(PDO::FETCH_ASSOC)[0];
		$this->spelstopp = $rad["spelstopp"];
		$this->komplett = (bool) $rad["komplett"];

		/**
		 * Populera fält för matcher, resultat och matchstatus.
		 */
		foreach (MATCHKOLUMNER as $match) {
			$nyckel = $match - 1;
			$this->match[$nyckel] = $rad["match$match"];
			$this->resultat[$nyckel] = $rad["resultat$match"];
			$this->matchstatus[$nyckel] = $rad["status$match"];
		}
	}

	/**
	 * Spara matcher till databas.
	 */
	public function spara_matcher(): void {
		/**
		 * Kompaktera och stränga matchdata.
		 */
		$cstr = implode(', ', array_map(fn (int $i): string => "`match$i`, `resultat$i`, `status$i`", MATCHKOLUMNER));
		$vstr = implode(', ', array_map(fn (int $i): string => ":match$i, :resultat$i, :status$i", MATCHKOLUMNER));

		/**
		 * Är alla matcher spelade och inte lottade?
		 */
		$this->komplett = !in_array(0, $this->matchstatus, true);

		/**
		 * Ersätt matchdata för aktuell omgång.
		 */
		$sats = $this->spel->db->instans->prepare("REPLACE INTO `matcher`
			(`omgång`, `speltyp`, `spelstopp`, `komplett`, $cstr) VALUES
			(:omgang, :speltyp, :spelstopp, :komplett, $vstr)");
		$sats->bindValue(':omgang', $this->spel->omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $this->spel->speltyp->value, PDO::PARAM_INT);
		$sats->bindValue(':spelstopp', $this->spelstopp, PDO::PARAM_STR);
		$sats->bindValue(':komplett', $this->komplett, PDO::PARAM_BOOL);

		/**
		 * Bind fält för matcher, resultat och matchstatus till platshållare.
		 */
		foreach (MATCHKOLUMNER as $match) {
			$nyckel = $match - 1;
			$sats->bindValue(":match$match", $this->match[$nyckel], PDO::PARAM_STR);
			$sats->bindValue(":resultat$match", $this->resultat[$nyckel], PDO::PARAM_STR);
			$sats->bindValue(":status$match", $this->matchstatus[$nyckel], PDO::PARAM_STR);
		}

		/**
		 * Logga.
		 */
		$kommentar = match ($sats->execute()) {
			true => ": ✅ Sparade matcher.",
			false => ": ❌ Kunde inte spara matcher."
		};

		$this->spel->db->logg->logga(self::class . "$kommentar ({$this->spel->omgång})");
	}
}
