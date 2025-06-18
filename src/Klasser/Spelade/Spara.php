<?php

/**
 * Klass Spara.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Spelade;

use PDO;
use Tips\Klasser\Utdelning;
use Tips\Klasser\Matcher;
use Tips\Klasser\Graf;

/**
 * Klass Spara.
 * Hämtar genererad data och sparar permanent i databas.
 * Renderar tipsgrafer.
 * Skriver rader till fil för uppladdning vid Svenska spel.
 */
class Spara {
	public Graf $graf;
	public int $antal_genererade = 0;		// antal genererade tipsrader
	public int $antal_utvalda_rader = 0;	// antal utvalda tipsrader
	public ?string $kommentar = null;		// spel- och moduldata
	protected string $tipsrader = '';		// tipsrader i kompakt strängformat
	protected string $mappnamn = '';		// mapp i vilken textfil sparas
	protected string $mapp = '';
	protected string $textfil = '';			// tipsrader i Svenska spels format
	protected string $bildfil = '';			// grafisk projektion av spelade rader
	/**
	 * @var string[] $tipsvektor
	 */
	public array $tipsvektor = [];

	/**
	 * Initiera.
	 * Lägg definitioner i konstruktorn.
	 */
	public function __construct(protected Utdelning $utdelning, protected Matcher $matcher) {
		$this->mappnamn = $this->utdelning->spel->filer->mappnamn(
			$this->utdelning->spel->omgång,
			$this->utdelning->spel->speltyp
		);
		$this->mapp = BAS . GENERERADE . $this->mappnamn;
		$this->textfil = "{$this->mapp}/{$this->utdelning->spel->filnamn}.txt";
		$this->bildfil = SPELADE . "/{$this->utdelning->spel->filnamn}.png";
		$this->graf = new Graf();
	}

	/**
	 * Spara genererade rader i databas.
	 */
	public function spara_genererade_tipsrader_db(): void {
		/**
		 * Hämta data från generering i temporär databas.
		 */
		$sats = $this->utdelning->spel->db->temp->prepare(
			"SELECT `tipsrader`, `datum`, `genererade`, `valda`, `kommentar`
			FROM `genererat` WHERE `omgång`=:omgang
			AND `speltyp`=:speltyp AND `sekvens`=:sekvens LIMIT 1"
		);
		$sats->bindValue(':omgang', $this->utdelning->spel->omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $this->utdelning->spel->speltyp->value, PDO::PARAM_INT);
		$sats->bindValue(':sekvens', $this->utdelning->spel->sekvens, PDO::PARAM_INT);
		$sats->bindColumn('tipsrader', $this->tipsrader, PDO::PARAM_STR);
		$sats->bindColumn('datum', $datum, PDO::PARAM_INT);
		$sats->bindColumn('genererade', $this->antal_genererade, PDO::PARAM_INT);
		$sats->bindColumn('valda', $this->antal_utvalda_rader, PDO::PARAM_INT);
		$sats->bindColumn('kommentar', $this->kommentar, PDO::PARAM_STR);
		$sats->execute();
		$sats->fetch(PDO::FETCH_OBJ);

		/**
		 * Ersätt data från generering i databas.
		 */
		$sats = $this->utdelning->spel->db->instans->prepare("REPLACE INTO `spelade`
			(`omgång`, `speltyp`, `sekvens`, `tipsrader`, `datum`, `genererade`, `valda`, `kommentar`)
			VALUES (:omgang, :speltyp, :sekvens, :tipsrader, :datum, :genererade, :valda, :kommentar)");
		$sats->bindValue(':omgang', $this->utdelning->spel->omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $this->utdelning->spel->speltyp->value, PDO::PARAM_INT);
		$sats->bindValue(':sekvens', $this->utdelning->spel->sekvens, PDO::PARAM_INT);
		$sats->bindValue(':tipsrader', $this->tipsrader, PDO::PARAM_STR);
		$sats->bindValue(':datum', $datum, PDO::PARAM_STR);
		$sats->bindValue(':genererade', $this->antal_genererade, PDO::PARAM_INT);
		$sats->bindValue(':valda', $this->antal_utvalda_rader, PDO::PARAM_INT);
		$sats->bindValue(':kommentar', $this->kommentar, PDO::PARAM_STR);

		/**
		 * Logga.
		 */
		$meddelande = match ($sats->execute()) {
			true => "✅ Sparade genererade tipsrader.",
			false => "❌ Kunde inte spara genererade tipsrader."
		};

		$this->utdelning->spel->db->logg->logga(self::class .
			": $meddelande ({$this->utdelning->spel->omgång}-{$this->utdelning->spel->sekvens}))");

		/**
		 * Packa upp tipsrader.
		 */
		$this->tipsvektor = bas36till3(explode(',', $this->tipsrader));
	}
}
